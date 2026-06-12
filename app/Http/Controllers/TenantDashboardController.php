<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Review;
use App\Models\Wishlist;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TenantDashboardController extends Controller
{
    /**
     * Dashboard Utama
     */
    public function dashboard(): View
    {
        $user = Auth::user();

        $bookingQuery = $user->bookings();

        $stats = [
            'active_bookings' => (clone $bookingQuery)
                ->where('status', Booking::STATUS_PAID)
                ->count(),
            'pending_payments' => (clone $bookingQuery)
                ->where('status', Booking::STATUS_PENDING)
                ->count(),
            'total_transactions' => Payment::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('booking_id', $user->bookings()->select('id'));
            })
                ->count(),
            'wishlist_count' => $user->wishlists()->count(),
        ];

        $activeBookings = Booking::where('user_id', $user->id)
            ->where('status', Booking::STATUS_PAID)
            ->with('room.property')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $recentPayments = Payment::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereIn('booking_id', $user->bookings()->select('id'));
        })
            ->with('booking')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recommendations = Property::where('status', 'active')
            ->inRandomOrder()
            ->take(6)
            ->with('rooms')
            ->get();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('tenant.dashboard', compact(
            'user',
            'stats',
            'activeBookings',
            'recentPayments',
            'recommendations',
            'notifications'
        ));
    }

    /**
     * Halaman Booking Saya
     */
    public function bookings(Request $request): View
    {
        $user = Auth::user();
        $status = $request->get('status', 'all');

        $query = Booking::where('user_id', $user->id)
            ->with('room.property', 'payment')
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(10);

        return view('tenant.bookings', compact('bookings', 'status'));
    }

    /**
     * Detail Booking
     */
    public function bookingDetail(Booking $booking): View
    {
        $this->authorize('view', $booking);

        $booking->load('room.property', 'payment');

        return view('tenant.booking-detail', compact('booking'));
    }

    /**
     * Halaman Pembayaran
     */
    public function payments(Request $request): View
    {
        $user = Auth::user();
        $bookingId = $request->get('booking_id');

        $booking = null;
        if ($bookingId) {
            $booking = Booking::findOrFail($bookingId);
            $this->authorize('view', $booking);
        }

        $payments = Payment::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereIn('booking_id', $user->bookings()->select('id'));
        })
            ->with('booking')
            ->latest()
            ->paginate(10);

        return view('tenant.payments', compact('payments', 'booking'));
    }

    /**
     * Detail Pembayaran
     */
    public function paymentDetail(Payment $payment): View
    {
        $this->authorize('view', $payment);

        $payment->load('booking.room.property');

        return view('tenant.payment-detail', compact('payment'));
    }

    /**
     * Halaman Wishlist
     */
    public function wishlist(): View
    {
        $user = Auth::user();

        $wishlists = Wishlist::where('user_id', $user->id)
            ->with('property.rooms')
            ->paginate(12);

        return view('tenant.wishlist', compact('wishlists'));
    }

    /**
     * Tambah ke Wishlist
     */
    public function addWishlist(Request $request, Property $property)
    {
        $user = Auth::user();

        Wishlist::firstOrCreate([
            'user_id' => $user->id,
            'property_id' => $property->id,
        ]);

        return back()->with('success', 'Properti ditambahkan ke wishlist!');
    }

    /**
     * Hapus dari Wishlist
     */
    public function removeWishlist(Property $property)
    {
        $user = Auth::user();

        Wishlist::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->delete();

        return back()->with('success', 'Properti dihapus dari wishlist!');
    }

    /**
     * Halaman Riwayat Transaksi
     */
    public function transactionHistory(): View
    {
        $user = Auth::user();

        $transactions = Payment::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhereIn('booking_id', $user->bookings()->select('id'));
        })
            ->with('booking.room.property')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('tenant.transaction-history', compact('transactions'));
    }

    /**
     * Download Invoice PDF
     */
    public function downloadInvoice(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load('user', 'booking.room.property');

        $invoiceNumber = $payment->invoice_number ?: 'INV-'.$payment->id;
        $filename = Str::slug($invoiceNumber).'.pdf';

        return Pdf::loadView('tenant.invoice', compact('payment', 'invoiceNumber'))
            ->setPaper('a4')
            ->download($filename);
    }

    /**
     * Halaman Profil
     */
    public function profile(): View
    {
        $user = Auth::user();

        return view('tenant.profile', compact('user'));
    }

    /**
     * Update Profil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|string|max:20|unique:users,phone,'.$user->id,
            'address' => 'required|string|max:500',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_photo_path')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo_path')->store('profile-photos', 'public');
            $validated['profile_photo_path'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Halaman Notifikasi
     */
    public function notifications(): View
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('tenant.notifications', compact('notifications'));
    }

    /**
     * Tandai Notifikasi Sebagai Dibaca
     */
    public function markNotificationAsRead(Notification $notification)
    {
        $this->authorize('view', $notification);

        $notification->markAsRead();

        return back();
    }

    /**
     * Cari Kos
     */
    public function searchProperties(Request $request): View
    {
        $query = Property::where('status', 'active');

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('location', 'like', '%'.$request->search.'%')
                    ->orWhere('city', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('min_price') && $request->filled('max_price')) {
            $minPrice = $request->min_price;
            $maxPrice = $request->max_price;

            $query->whereHas('rooms', function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price_per_month', [$minPrice, $maxPrice]);
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', '>=', $request->rating);
        }

        $properties = $query->with('rooms')
            ->paginate(12);

        $user = Auth::user();
        $wishlists = Wishlist::where('user_id', $user->id)
            ->pluck('property_id')
            ->toArray();

        return view('tenant.search-properties', compact('properties', 'wishlists'));
    }

    /**
     * Detail Properti
     */
    public function propertyDetail(Property $property): View
    {
        $property->load('rooms', 'reviews');

        $user = Auth::user();
        $isWishlisted = Wishlist::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->exists();

        $reviews = Review::where('property_id', $property->id)
            ->with('user')
            ->paginate(5);

        return view('tenant.property-detail', compact('property', 'isWishlisted', 'reviews'));
    }

    public function createBooking(Request $request, Property $property)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer'],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:24'],
        ]);

        $room = $property->rooms()
            ->whereKey($validated['room_id'])
            ->where('status', 'available')
            ->firstOrFail();
        $durationMonths = (int) $validated['duration_months'];
        $adminFee = 25000;
        $total = ((int) $room->price_per_month * $durationMonths) + $adminFee;

        $booking = Auth::user()->bookings()->create([
            'room_id' => $room->id,
            'kos_name' => $property->name,
            'room_type' => $room->room_type.' - '.$room->room_number,
            'location' => $property->location,
            'tenant_name' => Auth::user()->name,
            'booking_date' => now(),
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => Carbon::parse($validated['check_in_date'])->addMonths($durationMonths),
            'duration_months' => $durationMonths,
            'price_per_month' => $room->price_per_month,
            'admin_fee' => $adminFee,
            'total_amount' => $total,
            'status' => Booking::STATUS_PENDING,
        ]);

        return redirect()->route('booking.payment.show', $booking)
            ->with('success', 'Booking dibuat. Silakan selesaikan pembayaran.');
    }
}

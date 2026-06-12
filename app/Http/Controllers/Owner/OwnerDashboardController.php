<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function dashboard()
    {
        $owner = auth()->user();
        $propertyIds = $owner->properties()->pluck('id');
        $roomIds = Room::whereIn('property_id', $propertyIds)->pluck('id');
        $bookings = Booking::whereIn('room_id', $roomIds);
        $payments = Payment::whereIn('booking_id', (clone $bookings)->select('id'));

        $stats = [
            'properties' => $propertyIds->count(),
            'rooms' => $roomIds->count(),
            'occupied' => Room::whereIn('id', $roomIds)->where('status', Room::STATUS_OCCUPIED)->count(),
            'available' => Room::whereIn('id', $roomIds)->where('status', Room::STATUS_AVAILABLE)->count(),
            'new_bookings' => (clone $bookings)->where('status', Booking::STATUS_PENDING)->count(),
            'monthly_revenue' => (clone $payments)->where('payment_status', Payment::STATUS_PAID)
                ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('gross_amount'),
        ];

        $revenueRows = (clone $payments)->where('payment_status', Payment::STATUS_PAID)
            ->where('paid_at', '>=', now()->subMonths(5)->startOfMonth())->get()
            ->groupBy(fn (Payment $payment) => $payment->paid_at?->month)
            ->map(fn ($rows) => $rows->sum('gross_amount'));
        $revenueChart = collect(range(5, 0))->map(fn ($offset) => now()->subMonths($offset))
            ->map(fn ($date) => ['label' => $date->translatedFormat('M'), 'value' => (float) ($revenueRows[$date->month] ?? 0)]);
        $bookingRows = (clone $bookings)->where('created_at', '>=', now()->subMonths(5)->startOfMonth())->get()
            ->groupBy(fn (Booking $booking) => $booking->created_at->month)
            ->map->count();
        $bookingChart = collect(range(5, 0))->map(fn ($offset) => now()->subMonths($offset))
            ->map(fn ($date) => ['label' => $date->translatedFormat('M'), 'value' => (int) ($bookingRows[$date->month] ?? 0)]);

        $properties = Property::whereIn('id', $propertyIds)->withCount('rooms')->withCount([
            'rooms as occupied_rooms_count' => fn ($query) => $query->where('status', Room::STATUS_OCCUPIED),
        ])->latest()->take(4)->get();
        $recentBookings = (clone $bookings)->with('user', 'room.property', 'payment')->latest()->take(6)->get();
        $calendarEvents = (clone $bookings)->whereNotNull('check_in_date')->with('room.property')->latest()->take(20)->get()
            ->flatMap(fn ($booking) => [
                ['title' => 'Check-in '.$booking->tenant_name, 'date' => $booking->check_in_date?->toDateString(), 'type' => 'check-in'],
                ['title' => 'Check-out '.$booking->tenant_name, 'date' => $booking->check_out_date?->toDateString(), 'type' => 'check-out'],
            ])->filter(fn ($event) => $event['date']);

        return view('owner.dashboard', compact('owner', 'stats', 'properties', 'recentBookings', 'revenueChart', 'bookingChart', 'calendarEvents'));
    }

    public function properties(Request $request)
    {
        $properties = auth()->user()->properties()->withCount('rooms')->latest()->paginate(12);

        return view('owner.manage', ['section' => 'properties', 'items' => $properties]);
    }

    public function rooms(Request $request)
    {
        $properties = auth()->user()->properties()->orderBy('name')->get();
        $items = Room::whereIn('property_id', $properties->pluck('id'))->with('property')
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))->latest()->paginate(20);

        return view('owner.manage', compact('items', 'properties') + ['section' => 'rooms']);
    }

    public function bookings(Request $request)
    {
        $items = $this->ownerBookings()->with('user', 'room.property', 'payment')
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))->latest()->paginate(20);

        return view('owner.manage', ['section' => 'bookings', 'items' => $items]);
    }

    public function tenants()
    {
        $items = $this->ownerBookings()->whereIn('status', [Booking::STATUS_PAID, Booking::STATUS_APPROVED])
            ->with('user', 'room.property')->latest()->paginate(20);

        return view('owner.manage', ['section' => 'tenants', 'items' => $items]);
    }

    public function payments(Request $request)
    {
        $items = Payment::whereIn('booking_id', $this->ownerBookings()->select('id'))->with('booking.user', 'booking.room.property')
            ->when($request->status, fn ($q, $status) => $q->where('payment_status', $status))->latest()->paginate(20);

        return view('owner.manage', ['section' => 'payments', 'items' => $items]);
    }

    public function revenue()
    {
        $items = Payment::whereIn('booking_id', $this->ownerBookings()->select('id'))->where('payment_status', Payment::STATUS_PAID)
            ->with('booking.room.property')->latest('paid_at')->paginate(20);

        return view('owner.manage', ['section' => 'revenue', 'items' => $items]);
    }

    public function reports()
    {
        return view('owner.manage', ['section' => 'reports', 'items' => collect()]);
    }

    public function notifications()
    {
        $items = Notification::whereIn('booking_id', $this->ownerBookings()->select('id'))->latest()->paginate(20);

        return view('owner.manage', ['section' => 'notifications', 'items' => $items]);
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        abort_unless($this->ownsBooking($booking), 403);
        $validated = $request->validate(['status' => ['required', 'in:approved,rejected']]);
        abort_unless($booking->status === Booking::STATUS_PENDING, 422, 'Booking sudah diproses.');

        DB::transaction(function () use ($booking, $validated): void {
            $booking->update(['status' => $validated['status']]);
            $booking->room?->update(['status' => $validated['status'] === Booking::STATUS_APPROVED ? Room::STATUS_OCCUPIED : Room::STATUS_AVAILABLE]);
        });

        return response()->json(['message' => $validated['status'] === Booking::STATUS_APPROVED ? 'Booking berhasil diterima.' : 'Booking berhasil ditolak.', 'status' => $validated['status']]);
    }

    private function ownerBookings()
    {
        return Booking::whereIn('room_id', Room::whereIn('property_id', auth()->user()->properties()->select('id'))->select('id'));
    }

    private function ownsBooking(Booking $booking): bool
    {
        return $booking->room?->property?->owner_id === auth()->id();
    }
}

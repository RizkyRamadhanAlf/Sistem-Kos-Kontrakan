<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    private const ACTIVE_BOOKING_STATUSES = ['active', Booking::STATUS_PAID, Booking::STATUS_APPROVED];

    public function index(): View
    {
        $complaints = Complaint::where('user_id', auth()->id())
            ->with('booking.room.property')
            ->latest()
            ->paginate(10);

        return view('tenant.complaints.index', compact('complaints'));
    }

    public function create(): View
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->whereIn('status', self::ACTIVE_BOOKING_STATUSES)
            ->with('room.property')
            ->latest()
            ->get();

        return view('tenant.complaints.create', [
            'bookings' => $bookings,
            'categories' => Complaint::categories(),
            'priorities' => Complaint::priorities(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer'],
            'category' => ['required', 'in:'.implode(',', array_keys(Complaint::categories()))],
            'priority' => ['required', 'in:'.implode(',', array_keys(Complaint::priorities()))],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10'],
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $booking = Booking::where('user_id', auth()->id())
            ->whereIn('status', self::ACTIVE_BOOKING_STATUSES)
            ->with('room.property')
            ->findOrFail($validated['booking_id']);

        $ownerId = $booking->room?->property?->owner_id;
        abort_unless($ownerId, 422, 'Booking belum terhubung dengan pemilik kos.');

        $complaint = DB::transaction(function () use ($request, $validated, $booking, $ownerId) {
            $complaint = Complaint::create([
                'ticket_number' => $this->generateTicketNumber(),
                'user_id' => auth()->id(),
                'owner_id' => $ownerId,
                'booking_id' => $booking->id,
                'category' => $validated['category'],
                'priority' => $validated['priority'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => Complaint::STATUS_PENDING,
            ]);

            foreach ($request->file('images', []) as $image) {
                $complaint->images()->create([
                    'image_path' => $image->store('complaint-images', 'public'),
                ]);
            }

            $this->notify(auth()->id(), 'Komplain berhasil dibuat', 'Tiket '.$complaint->ticket_number.' berhasil dikirim dan menunggu ditinjau pemilik kos.', $complaint);
            $this->notify($ownerId, 'Komplain baru masuk', auth()->user()->name.' mengirim komplain '.$complaint->ticket_number.'.', $complaint);

            return $complaint;
        });

        return redirect()->route('tenant.complaints.show', $complaint)
            ->with('success', 'Komplain berhasil dibuat.');
    }

    public function show(Complaint $complaint): View
    {
        abort_unless($complaint->user_id === auth()->id(), 403);
        $complaint->load('booking.room.property', 'images', 'replies.user');

        return view('tenant.complaints.show', compact('complaint'));
    }

    public function reply(Request $request, Complaint $complaint)
    {
        abort_unless($complaint->user_id === auth()->id(), 403);
        abort_if(in_array($complaint->status, [Complaint::STATUS_RESOLVED, Complaint::STATUS_REJECTED], true), 422, 'Komplain sudah ditutup.');

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $complaint->replies()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        $this->notify($complaint->owner_id, 'Balasan komplain diterima', auth()->user()->name.' membalas tiket '.$complaint->ticket_number.'.', $complaint);

        return back()->with('success', 'Tanggapan berhasil dikirim.');
    }

    private function generateTicketNumber(): string
    {
        do {
            $ticket = 'KMP-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));
        } while (Complaint::where('ticket_number', $ticket)->exists());

        return $ticket;
    }

    private function notify(int $userId, string $title, string $message, Complaint $complaint): void
    {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'complaint',
            'booking_id' => $complaint->booking_id,
            'complaint_id' => $complaint->id,
        ]);
    }
}

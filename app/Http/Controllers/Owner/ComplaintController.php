<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $complaints = Complaint::where('owner_id', auth()->id())
            ->with('user', 'booking.room.property')
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15);

        return view('owner.complaints.index', [
            'complaints' => $complaints,
            'statuses' => Complaint::statuses(),
        ]);
    }

    public function show(Complaint $complaint): View
    {
        $this->authorizeOwner($complaint);
        $complaint->load('user', 'booking.room.property', 'images', 'replies.user');

        return view('owner.complaints.show', [
            'complaint' => $complaint,
            'statuses' => Complaint::statuses(),
        ]);
    }

    public function reply(Request $request, Complaint $complaint)
    {
        $this->authorizeOwner($complaint);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $complaint->replies()->create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        $this->notify($complaint->user_id, 'Pemilik kos memberi tanggapan', 'Ada tanggapan baru pada tiket '.$complaint->ticket_number.'.', $complaint);

        return back()->with('success', 'Tanggapan berhasil dikirim.');
    }

    public function updateStatus(Request $request, Complaint $complaint)
    {
        $this->authorizeOwner($complaint);

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Complaint::statuses()))],
        ]);

        if ($complaint->status !== $validated['status']) {
            $complaint->update(['status' => $validated['status']]);
            $this->notify($complaint->user_id, 'Status komplain berubah', 'Tiket '.$complaint->ticket_number.' sekarang berstatus '.$complaint->status_label.'.', $complaint);
        }

        return back()->with('success', 'Status komplain berhasil diperbarui.');
    }

    private function authorizeOwner(Complaint $complaint): void
    {
        abort_unless($complaint->owner_id === auth()->id(), 403);
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

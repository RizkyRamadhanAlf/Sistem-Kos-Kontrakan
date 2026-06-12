<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $property = Property::where('owner_id', auth()->id())->findOrFail($data['property_id']);
        $property->rooms()->create($data);

        return back()->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function update(Request $request, Room $room)
    {
        $this->authorizeOwner($room);
        $data = $this->validatedData($request);
        Property::where('owner_id', auth()->id())->findOrFail($data['property_id']);
        $room->update($data);

        return back()->with('success', 'Informasi kamar berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Room $room)
    {
        $this->authorizeOwner($room);
        $data = $request->validate(['status' => ['required', 'in:available,booked,occupied']]);
        $room->update($data);

        return response()->json(['message' => 'Status kamar diperbarui.', 'status' => $room->status]);
    }

    public function destroy(Room $room)
    {
        $this->authorizeOwner($room);
        abort_if($room->bookings()->whereIn('status', ['paid', 'approved'])->exists(), 422, 'Kamar masih memiliki penyewa aktif.');
        $room->delete();

        return response()->json(['message' => 'Kamar berhasil dihapus.']);
    }

    private function authorizeOwner(Room $room): void
    {
        abort_unless($room->property?->owner_id === auth()->id(), 403);
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'property_id' => ['required', 'integer'],
            'room_number' => ['required', 'string', 'max:50'],
            'room_type' => ['required', 'string', 'max:100'],
            'price_per_month' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
            'image_url' => ['nullable', 'url'],
            'status' => ['required', 'in:available,booked,occupied'],
        ]);

        return $data;
    }
}

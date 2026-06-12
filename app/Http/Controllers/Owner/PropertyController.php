<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function store(Request $request)
    {
        auth()->user()->properties()->create($this->validated($request));

        return back()->with('success', 'Properti berhasil ditambahkan.');
    }

    public function update(Request $request, Property $property)
    {
        $this->authorizeOwner($property);
        $property->update($this->validated($request));

        return back()->with('success', 'Properti berhasil diperbarui.');
    }

    public function destroy(Property $property)
    {
        $this->authorizeOwner($property);
        abort_if($property->rooms()->whereHas('bookings', fn ($q) => $q->whereIn('status', ['paid', 'approved']))->exists(), 422, 'Properti masih memiliki penyewa aktif.');
        $property->delete();

        return response()->json(['message' => 'Properti berhasil dihapus.']);
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'facilities' => ['nullable', 'array'],
            'facilities.*' => ['string', 'max:100'],
            'rules' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $data['facilities'] = $request->input('facilities', []);

        return $data;
    }

    private function authorizeOwner(Property $property): void
    {
        abort_unless($property->owner_id === auth()->id(), 403);
    }
}

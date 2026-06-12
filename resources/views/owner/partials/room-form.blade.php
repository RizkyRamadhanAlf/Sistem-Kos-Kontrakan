@php
    $room = $room ?? null;
@endphp

<label class="form-label small fw-semibold">Properti</label>
<select class="form-select mb-3" name="property_id" required>
    @foreach($properties as $property)
        <option value="{{ $property->id }}" @selected(old('property_id', $room?->property_id) == $property->id)>
            {{ $property->name }}
        </option>
    @endforeach
</select>

<div class="row g-2">
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Nomor Kamar</label>
        <input class="form-control" name="room_number" value="{{ old('room_number', $room?->room_number) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Tipe Kamar</label>
        <input class="form-control" name="room_type" value="{{ old('room_type', $room?->room_type) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Harga per Bulan</label>
        <input class="form-control" type="number" min="0" name="price_per_month" value="{{ old('price_per_month', $room?->price_per_month) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label small fw-semibold">Kapasitas</label>
        <input class="form-control" type="number" min="1" name="capacity" value="{{ old('capacity', $room?->capacity ?? 1) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label small fw-semibold">Status</label>
        <select class="form-select" name="status" required>
            @foreach(['available' => 'Tersedia', 'booked' => 'Dibooking', 'occupied' => 'Terisi'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $room?->status ?? 'available') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label small fw-semibold">URL Foto Kamar</label>
        <input class="form-control" type="url" name="image_url" value="{{ old('image_url', $room?->image_url) }}">
    </div>
</div>

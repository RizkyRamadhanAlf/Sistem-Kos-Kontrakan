@php
    $property = $property ?? null;
    $selectedFacilities = old('facilities', $property?->facilities ?? []);
    $facilityOptions = ['WiFi', 'AC', 'Kamar Mandi Dalam', 'Parkir', 'CCTV', 'Dapur Bersama', 'Laundry', 'Keamanan 24 Jam', 'Listrik', 'Air Bersih'];
@endphp

<div class="row g-2">
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Nama Properti</label>
        <input class="form-control" name="name" value="{{ old('name', $property?->name) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Lokasi / Alamat</label>
        <input class="form-control" name="location" value="{{ old('location', $property?->location) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Kota</label>
        <input class="form-control" name="city" value="{{ old('city', $property?->city) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label small fw-semibold">Provinsi</label>
        <input class="form-control" name="province" value="{{ old('province', $property?->province) }}">
    </div>
    <div class="col-md-9">
        <label class="form-label small fw-semibold">URL Foto Properti</label>
        <input class="form-control" type="url" name="image_url" value="{{ old('image_url', $property?->image_url) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label small fw-semibold">Status</label>
        <select class="form-select" name="status" required>
            <option value="active" @selected(old('status', $property?->status ?? 'active') === 'active')>Aktif</option>
            <option value="inactive" @selected(old('status', $property?->status) === 'inactive')>Tidak Aktif</option>
        </select>
    </div>
</div>

<label class="form-label small fw-semibold mt-3">Deskripsi Properti</label>
<textarea class="form-control" name="description" rows="3" placeholder="Jelaskan suasana, lokasi, dan keunggulan kos...">{{ old('description', $property?->description) }}</textarea>

<label class="form-label small fw-semibold mt-3">Fasilitas Properti</label>
<div class="row g-2">
    @foreach($facilityOptions as $facility)
        <div class="col-6 col-md-4">
            <label class="border rounded-3 p-2 w-100">
                <input class="form-check-input me-1" type="checkbox" name="facilities[]" value="{{ $facility }}" @checked(in_array($facility, $selectedFacilities, true))>
                <span class="small">{{ $facility }}</span>
            </label>
        </div>
    @endforeach
</div>

<label class="form-label small fw-semibold mt-3">Aturan Kos</label>
<textarea class="form-control" name="rules" rows="4" placeholder="- Tidak menerima tamu menginap.&#10;- Menjaga kebersihan lingkungan kos.">{{ old('rules', $property?->rules) }}</textarea>

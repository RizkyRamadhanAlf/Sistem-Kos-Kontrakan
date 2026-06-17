<h1>Edit Kamar</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('kamar.update', $kamar->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Kost</label>
    <select name="kost_id">
        @foreach($kosts as $kost)
            <option value="{{ $kost->id }}"
                {{ $kost->id == $kamar->kost_id ? 'selected' : '' }}>
                {{ $kost->nama_kost }}
            </option>
        @endforeach
    </select>

    <br><br>

    <label>Nomor Kamar</label>
    <input type="text"
           name="nomor_kamar"
           value="{{ $kamar->nomor_kamar }}">

    <br><br>

    <label>Harga</label>
    <input type="number"
           name="harga"
           value="{{ $kamar->harga }}">

    <br><br>

    <label>Kapasitas</label>
    <input type="number"
           name="kapasitas"
           value="{{ $kamar->kapasitas }}">

    <br><br>

    <label>Status</label>
    <select name="status">
        <option value="tersedia"
            {{ $kamar->status == 'tersedia' ? 'selected' : '' }}>
            Tersedia
        </option>

        <option value="terisi"
            {{ $kamar->status == 'terisi' ? 'selected' : '' }}>
            Terisi
        </option>
    </select>

    <br><br>

    <a href="{{ route('kamar.index') }}">
        Kembali
    </a>

    <button type="submit">
        Update
    </button>
</form>

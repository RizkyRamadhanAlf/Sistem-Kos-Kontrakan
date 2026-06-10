<h1>Tambah Kamar</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('kamar.store') }}" method="POST">
    @csrf

    <label>Kost</label>
    <select name="kost_id">
        @foreach($kosts as $kost)
            <option value="{{ $kost->id }}">
                {{ $kost->nama_kost }}
            </option>
        @endforeach
    </select>

    <br><br>

    <label>Nomor Kamar</label>
    <input type="text" name="nomor_kamar">

    <br><br>

    <label>Harga</label>
    <input type="number" name="harga">

    <br><br>

    <label>Kapasitas</label>
    <input type="number" name="kapasitas">

    <br><br>

    <label>Status</label>

    <select name="status">
        <option value="tersedia">
            Tersedia
        </option>

        <option value="terisi">
            Terisi
        </option>
    </select>

    <br><br>

    <a href="{{ route('kamar.index') }}">
        Kembali
    </a>

    <button type="submit">
        Simpan
    </button>
</form>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kamar</title>
</head>
<body>

    <a href="{{ route('kamar.create') }}">
        Tambah Kamar
    </a>

    <h1>Daftar Kamar</h1>

    @if(session('success'))
        <p>
            {{ session('success') }}
        </p>
    @endif

    @forelse($kamars as $kamar)

        <div>

            <h3>Kamar {{ $kamar->nomor_kamar }}</h3>

            <p>Kost: {{ $kamar->kost->nama_kost }}</p>

            <p>Harga: {{ $kamar->harga }}</p>

            <p>Kapasitas: {{ $kamar->kapasitas }}</p>

            <p>Status: {{ $kamar->status }}</p>

            <a href="{{ route('kamar.show', $kamar->id) }}">
                Detail
            </a>

            <a href="{{ route('kamar.edit', $kamar->id) }}">
                Edit
            </a>

            <form action="{{ route('kamar.destroy', $kamar->id) }}"
                  method="POST"
                  style="display:inline;">

                @csrf
                @method('DELETE')

                <button type="submit">
                    Delete
                </button>

            </form>

            <hr>

        </div>

    @empty

        <p>Belum ada data kamar.</p>

    @endforelse

</body>
</html>

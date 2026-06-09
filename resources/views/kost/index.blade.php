<!DOCTYPE html>
<html>
<head>
    <title>Daftar Kost</title>
</head>
<body>

    <a href="{{ route('kost.create') }}">
        Tambah Kost
    </a>

    <br><br>

    <h1>Daftar Kost</h1>

    @forelse($kosts as $kost)
        <div>
            <h3>{{ $kost->nama_kost }}</h3>
            <p>{{ $kost->alamat }}</p>

            <a href="{{ route('kost.edit', $kost->id) }}">
                Edit
            </a>

            <a href="{{ route('kost.show', $kost->id) }}">
                Detail
            </a>

            <form action="{{ route('kost.destroy', $kost->id) }}"
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
        <p>Belum ada data kost.</p>
    @endforelse

</body>
</html>

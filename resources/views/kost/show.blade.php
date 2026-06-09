<h1>{{ $kost->nama_kost }}</h1>

<p>Alamat: {{ $kost->alamat }}</p>

<p>Harga: {{ $kost->harga_mulai }}</p>

<a href="{{ route('kost.index') }}">
    Kembali
</a>

<h1>Detail Kamar</h1>

<p>Nomor Kamar: {{ $kamar->nomor_kamar }}</p>

<p>Kost: {{ $kamar->kost->nama_kost }}</p>

<p>Harga: {{ $kamar->harga }}</p>

<p>Kapasitas: {{ $kamar->kapasitas }}</p>

<p>Status: {{ $kamar->status }}</p>

<a href="{{ route('kamar.index') }}">
    Kembali
</a>

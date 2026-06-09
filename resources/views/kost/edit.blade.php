<!DOCTYPE html>
<html>
<head>
    <title>Edit Kost</title>
</head>
<body>

<h1>Edit Kost</h1>

<form action="{{ route('kost.update', $kost->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Nama Kost</label>
        <input type="text"
               name="nama_kost"
               value="{{ $kost->nama_kost }}">
    </div>

    <br>

    <div>
        <label>Alamat</label>
        <input type="text"
               name="alamat"
               value="{{ $kost->alamat }}">
    </div>

    <br>

    <div>
        <label>Harga Mulai</label>
        <input type="number"
               name="harga_mulai"
               value="{{ $kost->harga_mulai }}">
    </div>

    <br>

    <button type="submit">
        Update
    </button>

</form>

</body>
</html>

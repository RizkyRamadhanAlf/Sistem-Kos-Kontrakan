<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kost</title>
</head>
<body>

    <h1>Tambah Kost</h1>

    <form action="{{ route('kost.store') }}" method="POST">
        @csrf

        <div>
            <label>Nama Kost</label>
            <input type="text" name="nama_kost">
        </div>

        <br>

        <div>
            <label>Alamat</label>
            <input type="text" name="alamat">
        </div>

        <br>

        <div>
            <label>Harga Mulai</label>
            <input type="number" name="harga_mulai">
        </div>

        <br>

        <button type="submit">
            Simpan
        </button>
    </form>

</body>
</html>

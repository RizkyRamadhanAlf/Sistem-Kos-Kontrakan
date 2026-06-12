@extends('admin.layout')

@section('title', 'Tambah Kost')
@section('header_title', 'Tambah Kost')

@section('content')

<div class="panel">

    <div class="panel-header mb-4">
        <h4 class="mb-1">Tambah Data Kost</h4>
        <p class="text-muted mb-0">
            Masukkan informasi kost yang akan ditambahkan.
        </p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kost.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Nama Kost
            </label>

            <input
                type="text"
                name="nama_kost"
                class="form-control"
                value="{{ old('nama_kost') }}"
                placeholder="Contoh: Kost Mawar Indah"
            >
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Alamat
            </label>

            <textarea
                name="alamat"
                rows="4"
                class="form-control"
                placeholder="Masukkan alamat lengkap kost"
            >{{ old('alamat') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">
                Harga Mulai
            </label>

            <div class="input-group">
                <span class="input-group-text">
                    Rp
                </span>

                <input
                    type="number"
                    name="harga_mulai"
                    class="form-control"
                    value="{{ old('harga_mulai') }}"
                    placeholder="500000"
                >
            </div>
        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('kost.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

            <button
                type="submit"
                class="btn btn-primary"
            >
                <i class="bi bi-check-circle-fill"></i>
                Simpan Kost
            </button>

        </div>

    </form>

</div>

@endsection

@extends('admin.layout')

@section('title', 'Edit Kost')
@section('header_title', 'Edit Kost')

@section('content')

<div class="panel">

    <div class="panel-header mb-4">
        <h4 class="mb-1">Edit Data Kost</h4>
        <p class="text-muted mb-0">
            Perbarui informasi kost yang sudah terdaftar.
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

    <form action="{{ route('kost.update', $kost->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label fw-semibold">
                Nama Kost
            </label>

            <input
                type="text"
                name="nama_kost"
                class="form-control"
                value="{{ old('nama_kost', $kost->nama_kost) }}"
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
            >{{ old('alamat', $kost->alamat) }}</textarea>
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
                    value="{{ old('harga_mulai', $kost->harga_mulai) }}"
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
                class="btn btn-warning"
            >
                <i class="bi bi-pencil-square"></i>
                Update Kost
            </button>

        </div>

    </form>

</div>

@endsection

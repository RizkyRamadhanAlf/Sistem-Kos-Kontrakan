@extends('admin.layout')

@section('title', 'Detail Kost')
@section('header_title', 'Detail Kost')

@section('content')

<div class="row">

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <div class="d-flex align-items-center gap-3 mb-4">

                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-house-door-fill fs-3 text-primary"></i>
                    </div>

                    <div>
                        <h3 class="mb-1">
                            {{ $kost->nama_kost }}
                        </h3>

                        <span class="text-muted">
                            Data Detail Kost
                        </span>
                    </div>

                </div>

                <hr>

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="text-muted small">
                            Nama Kost
                        </label>

                        <h5>
                            {{ $kost->nama_kost }}
                        </h5>

                    </div>

                    <div class="col-md-6">

                        <label class="text-muted small">
                            Harga Mulai
                        </label>

                        <h5 class="text-success">
                            Rp {{ number_format($kost->harga_mulai, 0, ',', '.') }}
                        </h5>

                    </div>

                    <div class="col-12">

                        <label class="text-muted small">
                            Alamat
                        </label>

                        <p class="mb-0">
                            {{ $kost->alamat }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h5 class="mb-3">
                    Aksi
                </h5>

                <div class="d-grid gap-2">

                    <a
                        href="{{ route('kost.edit', $kost->id) }}"
                        class="btn btn-warning"
                    >
                        <i class="bi bi-pencil-square"></i>
                        Edit Kost
                    </a>

                    <a
                        href="{{ route('kost.index') }}"
                        class="btn btn-outline-secondary"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Kembali
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

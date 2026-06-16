@extends('admin.layout')

@section('title', 'Manajemen Kamar')
@section('header_title', 'Manajemen Kamar')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total Kamar</h6>
                <h3 class="fw-bold mb-0">
                    {{ $kamars->count() }}
                </h3>
            </div>
        </div>
    </div>

</div>

<div class="panel">

    <div class="panel-header d-flex justify-content-between align-items-center mb-4">

        <div>
            <h5 class="mb-1">Daftar Kamar</h5>
            <p class="text-muted mb-0">
                Kelola seluruh data kamar kost.
            </p>
        </div>

        <a
            href="{{ route('kamar.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle-fill"></i>
            Tambah Kamar
        </a>

    </div>

    <div class="row g-4">

        @forelse($kamars as $kamar)

            <div class="col-lg-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start mb-3">

                            <div>
                                <h5 class="mb-1">
                                    Kamar {{ $kamar->nomor_kamar }}
                                </h5>

                                <small class="text-muted">
                                    {{ $kamar->kost->nama_kost }}
                                </small>
                            </div>

                            @if($kamar->status == 'tersedia')
                                <span class="badge bg-success">
                                    Tersedia
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Terisi
                                </span>
                            @endif

                        </div>

                        <hr>

                        <p class="mb-2">
                            <strong>Harga:</strong>
                            Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                        </p>

                        <p class="mb-3">
                            <strong>Kapasitas:</strong>
                            {{ $kamar->kapasitas }} Orang
                        </p>

                        <div class="d-flex gap-2">

                            <a
                                href="{{ route('kamar.show', $kamar->id) }}"
                                class="btn btn-sm btn-outline-info"
                            >
                                <i class="bi bi-eye-fill"></i>
                            </a>

                            <a
                                href="{{ route('kamar.edit', $kamar->id) }}"
                                class="btn btn-sm btn-outline-warning"
                            >
                                <i class="bi bi-pencil-fill"></i>
                            </a>

                            <form
                                action="{{ route('kamar.destroy', $kamar->id) }}"
                                method="POST"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Hapus kamar ini?')"
                                >
                                    <i class="bi bi-trash-fill"></i>
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="alert alert-secondary text-center">
                    Belum ada data kamar.
                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection

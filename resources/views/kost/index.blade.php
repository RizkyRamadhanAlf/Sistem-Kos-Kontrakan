@extends('admin.layout')

@section('title', 'Manajemen Kost')
@section('header_title', 'Manajemen Kost')

@section('content')

<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total Kost</h6>
                <h3 class="fw-bold mb-0">{{ $kosts->count() }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted mb-1">Hasil Pencarian</h6>
                <h3 class="fw-bold mb-0">{{ $kosts->count() }}</h3>
            </div>
        </div>
    </div>

</div>

<div class="panel">

    <div class="panel-header d-flex justify-content-between align-items-center mb-3">

        <div>
            <h5 class="panel-title mb-1">Daftar Kost</h5>
            <p class="text-muted mb-0">
                Kelola seluruh data kost yang tersedia.
            </p>
        </div>

        <a href="{{ route('kost.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill"></i>
            Tambah Kost
        </a>

    </div>

    <form
        method="GET"
        action="{{ route('kost.index') }}"
        class="mb-4"
    >
        <div class="input-group">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Cari nama kost..."
                value="{{ request('search') }}"
            >

            <button class="btn btn-primary" type="submit">
                <i class="bi bi-search"></i>
                Cari
            </button>

        </div>
    </form>

    <div class="table-responsive">

        <table class="table align-middle">

            <thead class="table-light">
                <tr>
                    <th>Nama Kost</th>
                    <th>Alamat</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($kosts as $kost)

                    <tr>

                        <td>
                            <strong>{{ $kost->nama_kost }}</strong>
                        </td>

                        <td>
                            {{ $kost->alamat }}
                        </td>

                        <td>

                            <div class="d-flex gap-2">

                                <a
                                    href="{{ route('kost.show', $kost->id) }}"
                                    class="btn btn-sm btn-outline-info"
                                >
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                <a
                                    href="{{ route('kost.edit', $kost->id) }}"
                                    class="btn btn-sm btn-outline-warning"
                                >
                                    <i class="bi bi-pencil-fill"></i>
                                </a>

                                <form
                                    action="{{ route('kost.destroy', $kost->id) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Hapus kost ini?')"
                                    >
                                        <i class="bi bi-trash-fill"></i>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            Belum ada data kost.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
```

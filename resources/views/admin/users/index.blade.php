@extends('admin.layout')

@section('title', 'KostKu — Manajemen User')

@section('header_title', 'Manajemen User')

@section('content')
<div class="panel">
    <div class="panel-header">
        <div>
            <h6 class="panel-title">Daftar Pengguna</h6>
            <p class="panel-sub">Kelola semua pengguna dalam sistem</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-add"><i class="bi bi-person-plus-fill"></i> Tambah User</a>
    </div>

    <div class="table-responsive">
        <table class="table tenant-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email / No. HP</th>
                    <th>Role</th>
                    <th>Join Pada</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://i.pravatar.cc/32?u={{ $user->email }}" class="tenant-pic" alt=""/>
                            <div>
                                <span class="t-name">{{ $user->name }}</span>
                                <span class="t-phone">ID: #{{ $user->id }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="d-block text-dark fw-500">{{ $user->email }}</span>
                            <span class="text-muted small">{{ $user->phone ?? '-' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="unit-tag">{{ ucfirst($user->role ?? 'Penyewa') }}</span>
                    </td>
                    <td class="text-muted small">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <span class="status-pill pill-active">Aktif</span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-ghost" title="Edit User"><i class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-ghost" title="Hapus User"><i class="bi bi-trash-fill text-danger"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection

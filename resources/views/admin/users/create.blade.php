@extends('admin.layout')

@section('title', 'KostKu — Tambah User')

@section('header_title', 'Tambah User Baru')

@section('content')
<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <div>
            <h6 class="panel-title">Form Pengguna Baru</h6>
            <p class="panel-sub">Lengkapi data di bawah untuk menambahkan pengguna baru</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-ghost" style="width: auto; padding: 0 15px;"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-600">Nama Lengkap</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Andi Wijaya" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="andi@example.com" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">No. HP</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08123456789">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Role</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="" disabled selected>Pilih Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Pemilik (Owner)</option>
                    <option value="tenant" {{ old('role') == 'tenant' ? 'selected' : '' }}>Penyewa (Tenant)</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-600">Alamat</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Alamat lengkap...">{{ old('address') }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top">
            <button type="submit" class="btn-add" style="width: 100%; justify-content: center; padding: 12px;">Simpan Pengguna</button>
        </div>
    </form>
</div>
@endsection

@push('css')
<style>
    .form-label { font-size: 13px; color: var(--text-secondary); margin-bottom: 6px; }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid var(--border);
        padding: 10px 14px;
        font-size: 14px;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
    }
</style>
@endpush

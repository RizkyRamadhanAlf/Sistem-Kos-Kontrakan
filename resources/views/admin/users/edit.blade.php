@extends('admin.layout')

@section('title', 'KostKu — Edit User')

@section('header_title', 'Edit Data User')

@section('content')
<div class="panel" style="max-width: 800px; margin: 0 auto;">
    <div class="panel-header">
        <div>
            <h6 class="panel-title">Edit Profil: {{ $user->name }}</h6>
            <p class="panel-sub">Perbarui informasi pengguna di bawah ini</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn-ghost" style="width: auto; padding: 0 15px;"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-600">Nama Lengkap</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">No. HP</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Role</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Pemilik (Owner)</option>
                    <option value="tenant" {{ old('role', $user->role) == 'tenant' ? 'selected' : '' }}>Penyewa (Tenant)</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label class="form-label fw-600">Alamat</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $user->address) }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12 mt-4">
                <div class="alert alert-info py-2" style="font-size: 12px; border-radius: 8px;">
                    <i class="bi bi-info-circle-fill me-1"></i> Biarkan password kosong jika tidak ingin mengubahnya.
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Password Baru</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-600">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>

        <div class="mt-4 pt-3 border-top">
            <button type="submit" class="btn-add" style="width: 100%; justify-content: center; padding: 12px;">Perbarui Data</button>
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

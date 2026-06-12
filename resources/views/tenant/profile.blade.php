@extends('tenant.layout')

@section('title', 'Profil Saya - KostKu')

@section('content')
<div class="content-header">
    <div class="header-title">
        <h1>Profil Saya</h1>
        <p>Kelola informasi akun Anda</p>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <!-- Edit Profil -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
            <h6 style="margin: 0 0 1.5rem; font-weight: 600;">Informasi Pribadi</h6>
            
            <form action="{{ route('tenant.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Foto Profil -->
                <div class="mb-3">
                    <label class="form-label">Foto Profil</label>
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <img src="{{ $user->profile_photo_url }}"
                            style="width: 100px; height: 100px; border-radius: 8px; object-fit: cover;">
                        <div style="flex: 1;">
                            <input type="file" class="form-control" name="profile_photo_path" accept="image/*">
                            <small class="form-text text-muted">Maksimal 2MB, format JPG/PNG</small>
                        </div>
                    </div>
                </div>

                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nomor Telepon -->
                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="3" required>{{ old('address', $user->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>

        <!-- Ubah Password -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <h6 style="margin: 0 0 1.5rem; font-weight: 600;">Ubah Password</h6>
            
            <form action="{{ route('tenant.profile.update-password') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary">Ubah Password</button>
            </form>
        </div>
    </div>

    <!-- Info Akun -->
    <div class="col-lg-4">
        <div style="background: white; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0;">
            <h6 style="margin: 0 0 1.5rem; font-weight: 600;">Informasi Akun</h6>
            
            <div style="margin-bottom: 1rem;">
                <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Tanggal Bergabung</p>
                <p style="margin: 0.25rem 0 0; font-weight: 600;">{{ $user->created_at->format('d M Y') }}</p>
            </div>

            <div style="margin-bottom: 1rem;">
                <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Tipe Pengguna</p>
                <p style="margin: 0.25rem 0 0; font-weight: 600;">Penyewa</p>
            </div>

            <div style="margin-bottom: 1rem;">
                <p style="margin: 0; font-size: 0.85rem; color: #64748b;">Status Verifikasi</p>
                <p style="margin: 0.25rem 0 0; font-weight: 600;">
                    <i class="bi bi-check-circle-fill" style="color: var(--success);"></i> Terverifikasi
                </p>
            </div>

            <hr>

            <button class="btn btn-danger w-100" onclick="if(confirm('Hapus akun? Tindakan ini tidak dapat dibatalkan.')) document.getElementById('deleteForm').submit();">
                <i class="bi bi-trash"></i> Hapus Akun
            </button>

            <form id="deleteForm" action="{{ route('profile.destroy') }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection

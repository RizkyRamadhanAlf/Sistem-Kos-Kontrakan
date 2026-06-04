# Sistem Kos & Kontrakan 🏠

Platform manajemen penyewaan properti (Kos/Kontrakan) yang dibangun menggunakan Laravel. Aplikasi ini dirancang untuk mempermudah Pemilik (Owner) dalam mengelola unit dan pembayaran, serta mempermudah Penyewa (Tenant) dalam melakukan pelaporan maintenance dan konfirmasi pembayaran.

## 🚀 Fitur Utama

- **Multi-Role Authentication**: Mendukung 3 level pengguna (Admin, Owner, Tenant) menggunakan **Spatie Laravel Permission**.
- **Dashboard Terpisah**: Tampilan dashboard yang dipersonalisasi sesuai dengan role masing-masing.
- **Manajemen Pembayaran**: Tenant dapat mengunggah bukti pembayaran, dan Owner dapat memverifikasi secara real-time.
- **Layanan Maintenance**: Tenant dapat mengajukan permintaan perbaikan unit melalui sistem.
- **Manajemen Pengguna**: Admin memiliki kendali penuh untuk mengelola akun pengguna di dalam sistem.

## 🛠️ Tech Stack

- **Framework**: [Laravel 12](https://laravel.com)
- **Database**: MySQL / SQLite
- **Role Management**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
- **Frontend**: Blade Templating, Tailwind CSS, Alpine.js (Laravel Breeze)
- **Auth**: Laravel Breeze (Session Based)

## 📋 Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js & NPM

## ⚙️ Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/sistem-kos-kontrakan.git
   cd sistem-kos-kontrakan
   ```

2. **Instal Dependensi**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database & Seeding**
   Jalankan migrasi untuk membuat tabel dan seeder untuk membuat Role serta akun Admin default.
   ```bash
   php artisan migrate --seed
   ```

## 🔐 Konfigurasi Role (Spatie)

Aplikasi ini menggunakan sistem Role-Based Access Control (RBAC). Daftar Role yang tersedia:

- `admin`: Manajemen penuh sistem dan pengguna.
- `owner`: Mengelola unit kos, memverifikasi pembayaran, dan mengelola maintenance.
- `tenant`: Melakukan pembayaran dan mengajukan maintenance.

Untuk memberikan role secara manual via Tinker:
```php
$user = User::find(1);
$user->assignRole('admin');
```

## 📁 Struktur Folder Penting

- `app/Http/Controllers/Auth`: Logika registrasi dan login (dengan assign role otomatis).
- `database/seeders/RolesSeed.php`: Konfigurasi awal Role dan akun Admin.
- `resources/views/layouts/navigation.blade.php`: Navigasi dinamis berbasis role.

## 📄 Lisensi

Proyek ini dikembangkan untuk tujuan pembelajaran dan manajemen internal. Silakan hubungi pengembang untuk informasi lebih lanjut.

# Dashboard Penyewa KostKu

Dashboard modern dan responsive untuk penyewa kos dengan fitur lengkap untuk mengelola booking, pembayaran, dan profil.

## 📋 Fitur Utama

### 1. **Dashboard Home**
- Statistik ringkas (Booking Aktif, Menunggu Pembayaran, Total Transaksi, Wishlist)
- Daftar booking aktif dengan detail kos
- Pembayaran terbaru
- Rekomendasi kos
- Notifikasi terbaru

### 2. **Booking Saya**
- Lihat semua booking dengan filter status
- Filter: Semua, Menunggu Pembayaran, Aktif, Selesai, Dibatalkan
- Detail booking lengkap
- Aksi bayar dan detail

### 3. **Cari Kos**
- Pencarian dengan multiple filter
  - Nama/lokasi kos
  - Kota
  - Rentang harga
  - Rating
- Grid view properti dengan informasi:
  - Foto kos
  - Nama dan lokasi
  - Rating dan jumlah review
  - Harga mulai dari
  - Fasilitas utama
  - Tombol detail dan wishlist

### 4. **Detail Kos**
- Galeri foto
- Informasi lengkap properti
- Deskripsi, fasilitas, peraturan
- Daftar kamar dengan harga
- Review pengguna dengan rating
- Tombol booking dan wishlist

### 5. **Pembayaran**
- Tabel semua transaksi pembayaran
- Filter dan sorting
- Invoice number, nama kos, total, metode, status
- Tombol aksi (Bayar, Detail)
- Integrasi Midtrans (siap diimplementasikan)

### 6. **Detail Pembayaran**
- Rincian pembayaran lengkap
- Status pembayaran
- Info pembayaran berhasil/gagal
- Tombol bayar, download invoice, kembali

### 7. **Wishlist**
- Lihat kos-kos favorit
- Hapus dari wishlist
- Lihat detail dan booking

### 8. **Riwayat Transaksi**
- Tabel semua transaksi dengan detail
- Download invoice (fitur siap)
- Pagination

### 9. **Profil**
- Edit profil: nama, email, telepon, alamat
- Upload foto profil
- Ubah password
- Info akun (tanggal bergabung, status verifikasi)
- Hapus akun

### 10. **Notifikasi**
- Notifikasi real-time
- Filter berdasarkan tipe
- Tandai sebagai dibaca
- Berbagai tipe: booking, pembayaran, promo

## 🏗️ Struktur Teknis

### Models
- **Property**: Properti/kos
- **Room**: Kamar dalam properti
- **Booking**: Booking dari penyewa
- **Payment**: Pembayaran
- **Wishlist**: Daftar favorit
- **Review**: Review properti
- **Notification**: Notifikasi sistem
- **User**: User (extended dari Laravel default)

### Controllers
- **TenantDashboardController**: Menangani semua logika dashboard penyewa

### Routes
Semua route dimulai dengan `/tenant/` dan dilindungi middleware `auth` dan `role:penyewa`:
```
/tenant/dashboard - Dashboard home
/tenant/cari-kos - Halaman cari kos
/tenant/detail-kos/{id} - Detail kos
/tenant/booking-saya - Booking list
/tenant/booking/{id} - Detail booking
/tenant/pembayaran - Payment list
/tenant/pembayaran/{id} - Detail payment
/tenant/wishlist - Wishlist
/tenant/riwayat-transaksi - Transaction history
/tenant/notifikasi - Notifications
/tenant/profil - Profile
```

### Policies
- **BookingPolicy**: Otorisasi akses booking
- **PaymentPolicy**: Otorisasi akses payment
- **NotificationPolicy**: Otorisasi akses notification

### Migrations
- `create_properties_table`
- `create_rooms_table`
- `create_wishlists_table`
- `create_reviews_table`
- `create_notifications_table`
- `update_tables_add_missing_columns`

### Seeders
- **PropertySeeder**: Contoh data properti, kamar, dan review
- **DatabaseSeeder**: Main seeder yang menjalankan semua seeder lain

## 🚀 Setup & Installation

### 1. **Run Migrations**
```bash
php artisan migrate
```

### 2. **Run Seeders**
```bash
php artisan db:seed
```

Test account:
- Email: `ahmad@student.com`
- Password: `password123`
- Role: `penyewa`

### 3. **Start Development Server**
```bash
php artisan serve
```

## 🎨 UI/UX Features

- **Responsive Design**: Mobile, tablet, desktop
- **Modern UI**: Bootstrap 5 + Custom CSS
- **Icons**: Bootstrap Icons + Lucide Icons
- **Color Scheme**:
  - Primary: #2563EB (Blue)
  - Success: #10B981 (Green)
  - Warning: #F59E0B (Yellow)
  - Danger: #EF4444 (Red)
  - Light: #F8FAFC (Light Gray)
  - Dark: #1E293B (Dark Gray)

- **Components**:
  - Sidebar navigation
  - Stat cards
  - Filter forms
  - Data tables
  - Grid cards
  - Status badges
  - Action buttons

## 🔐 Security

- Role-based access control (RBAC)
- Policy-based authorization
- User can only access their own data
- CSRF protection
- Input validation

## 📦 Dependencies

- Laravel 12
- Bootstrap 5
- Bootstrap Icons
- jQuery (optional, tidak dimandakan)

## 🔄 Integrasi Midtrans (Siap Implementasi)

Persiapan untuk payment gateway:
- Model Payment sudah memiliki field untuk snap_token
- Controller sudah memiliki placeholder untuk Midtrans
- Front-end sudah siap untuk popup payment

Untuk implementasi lengkap:
1. Install `midtrans/midtrans-php`
2. Set MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY di .env
3. Implementasi method `createSnapToken` di controller

## 📝 Future Enhancements

- Dark mode toggle
- Real-time notifications dengan WebSocket
- Chat dengan pemilik kos
- Rating dan review
- Invoice PDF generation
- Email notifications
- SMS notifications
- Push notifications
- Advanced analytics
- Booking management
- Contract digital signature

## 📚 Conventions

### File Naming
- Controllers: `TenantDashboardController`
- Models: Singular (`Property`, `Room`)
- Migrations: `create_[table]_table`
- Views: `tenant/[page].blade.php`
- Seeders: `[Model]Seeder`

### Route Naming
- Prefix: `tenant.`
- Format: `tenant.action` atau `tenant.resource.action`
- RESTful untuk CRUD operations

### CSS Classes
- BEM methodology di custom CSS
- Bootstrap utility classes

## 🐛 Troubleshooting

### Dashboard tidak muncul
- Pastikan user role adalah `penyewa`
- Check routes middleware
- Verify user login

### Style tidak muncul
- Run `npm install && npm run build`
- Check @vite directive di layout

### Database error
- Run migration: `php artisan migrate --fresh`
- Run seeder: `php artisan db:seed`

## 📞 Support

Untuk pertanyaan atau issue, silakan hubungi tim development KostKu.

---

**Last Updated**: 2026-06-09
**Version**: 1.0.0

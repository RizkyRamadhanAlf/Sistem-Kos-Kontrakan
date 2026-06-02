# 📊 Dokumentasi Dashboard KostKu

## Ringkasan Perubahan

Saya telah membuat sistem dashboard lengkap untuk KostKu dengan dua dashboard terpisah:

### 1. **Dashboard Admin (Pemilik)**
- **URL**: `/pemilik`
- **Aksesibilitas**: Hanya user yang sudah login
- **Fitur Utama**:
  - 📊 Statistik: Total Properti, Kamar Tersewa, Pemasukan, Tunggakan
  - 🏠 Daftar Properti dengan status okupansi
  - 💰 Daftar Jatuh Tempo Pembayaran
  - 🔧 Manajemen Permintaan Pemeliharaan
  - 👤 User info di sidebar

### 2. **Dashboard Tenant (User)**
- **URL**: `/tenant`
- **Aksesibilitas**: Hanya user yang sudah login
- **Fitur Utama**:
  - 📋 Informasi Kontrak Sewa
  - 📊 Status Pembayaran Bulan Ini
  - 💳 Riwayat Pembayaran (5 terakhir)
  - ⚡ Aksi Cepat: Kirim Pembayaran, Lapor Maintenance, Support
  - ℹ️ Info Penting & Jadwal Pembayaran
  - 📢 Pengumuman Terbaru

### 3. **Halaman Depan (Home)**
- **URL**: `/` (root)
- **Aksesibilitas**: Publik
- **Fitur**:
  - Dua tombol untuk memilih dashboard (Admin/Tenant)
  - Daftar fitur di setiap dashboard

---

## 📁 File yang Dibuat/Dimodifikasi

### File Baru:
```
resources/views/tenant/dashboard.blade.php     ✅ Dashboard tenant baru
resources/views/index.blade.php                ✅ Halaman depan dengan pilihan dashboard
```

### File yang Dimodifikasi:
```
routes/web.php                                  ✅ Update routing dengan struktur baru
```

---

## 🔐 Struktur Routing

```php
// Root
GET  /                          → view('index')                    [PUBLIC]

// Admin Routes
GET  /pemilik                   → Dashboard Admin                  [AUTH]
GET  /pemilik/maintenance       → Daftar Maintenance              [AUTH]
POST /pemilik/maintenance/{id}  → Update Status Maintenance       [AUTH]

// Tenant Routes
GET  /tenant                    → Dashboard Tenant                 [AUTH]

// Payment Routes (Protected)
GET  /pembayaran                → Upload Payment (Tenant)          [AUTH]
POST /pembayaran                → Store Payment (Tenant)           [AUTH]
GET  /pembayaran/verifikasi     → Verify Payment (Admin)          [AUTH]
POST /pembayaran/{id}/verifikasi → Verify Payment Action (Admin)  [AUTH]

// Maintenance Routes (Protected)
GET  /maintenance               → List Maintenance Requests        [AUTH]
POST /maintenance               → Create Maintenance Request       [AUTH]
```

---

## 🎨 Tema & Styling

Kedua dashboard menggunakan:

- **Font**: Plus Jakarta Sans, DM Serif Display, Bootstrap Icons
- **Framework**: Bootstrap 5.3.3
- **Warna Utama**:
  - Teal: `#0d9488` (Aksen utama)
  - Amber: `#d97706` (Sekunder)
  - Green: `#16a34a` (Positif)
  - Rose: `#e11d48` (Alert/Danger)

- **Sidebar**: Dark mode (`#0f1923`)
- **Background**: Light gray (`#f4f6f9`)

---

## 🔑 Fitur Pembayaran

### ⚠️ **PENTING - Pembayaran Hanya untuk Tenant**

Pembayaran sekarang hanya dapat diakses oleh user yang sudah login:

✅ **Akses**: `/pembayaran` (perlu auth)
✅ **Fitur Upload**: Tenant bisa upload bukti pembayaran
✅ **Fitur Verifikasi**: Admin bisa verifikasi pembayaran
✅ **Route Protection**: Menggunakan middleware `auth`

---

## 📝 Status Pembayaran

Payment Model mempunyai 3 status:

1. **PENDING**: Menunggu upload dari tenant
2. **VERIFIED**: Sudah diverifikasi admin (Lunas)
3. **REJECTED**: Ditolak oleh admin

---

## 🚀 Cara Menggunakan

### Untuk Admin (Pemilik):
1. Login ke sistem
2. Akses `/pemilik` untuk melihat dashboard
3. Kelola properti, lihat pembayaran, manage maintenance

### Untuk Tenant (User):
1. Login ke sistem
2. Akses `/tenant` untuk melihat dashboard
3. Lihat kontrak, kirim pembayaran, lapor maintenance

### Untuk Publik:
1. Akses `/` untuk halaman depan
2. Pilih "Dashboard Admin" atau "Dashboard Tenant"
3. Akan redirect ke login jika belum authenticated

---

## 📊 Customization Options

### Mengubah Warna Tema:
Edit file `resources/css/style.css`:

```css
:root {
  --teal: #0d9488;        /* Ubah warna utama */
  --amber: #d97706;       /* Ubah warna sekunder */
  --green: #16a34a;       /* Ubah warna positif */
  --rose: #e11d48;        /* Ubah warna alert */
}
```

### Menambahkan Menu di Sidebar:
Buka file dashboard masing-masing dan tambahkan di section `sidebar-nav`:

```html
<a href="/path/menu" class="nav-item">
  <i class="bi bi-icon-name"></i> Menu Name
  <span class="badge-nav">count</span>
</a>
```

---

## 🔐 Security Notes

✅ Semua dashboard dilindungi middleware `auth`
✅ Payment hanya bisa diakses user yang sudah login
✅ Admin hanya bisa verifikasi di `/pembayaran/verifikasi`

---

## 📱 Responsive Design

Semua dashboard sudah responsive untuk:
- 📱 Mobile (< 576px)
- 📱 Tablet (576px - 992px)
- 🖥️ Desktop (> 992px)

---

## 🎯 Next Steps (Optional)

Untuk pengembangan lebih lanjut:

1. **Tambah Role Management**: Gunakan middleware untuk membedakan Admin/Tenant
2. **Database Integration**: Hubungkan dengan data real dari database
3. **Chart & Analytics**: Tambahkan chart untuk statistik pembayaran
4. **Notification System**: Email/SMS notification untuk pembayaran
5. **Mobile App**: Convert dashboard ke PWA atau mobile app

---

## ❓ FAQ

**Q: Bagaimana cara user membedakan antara dashboard admin dan tenant?**
A: User akan diarahkan ke halaman depan (`/`) yang menampilkan dua pilihan. Jika sudah login, mereka bisa akses sesuai role mereka.

**Q: Bagaimana jika user belum login?**
A: Akan redirect ke halaman login (Laravel default).

**Q: Pembayaran bisa diakses siapa saja?**
A: Tidak, hanya user yang sudah authenticated (setelah login).

**Q: Bagaimana cara merubah informasi di dashboard tenant?**
A: Edit file `resources/views/tenant/dashboard.blade.php` dan ubah data statis menjadi data dynamic dari database.

---

**Version**: 1.0  
**Last Updated**: 29 Mei 2025  
**Theme**: KostKu Dashboard System

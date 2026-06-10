@extends('admin.layout')

@section('title', 'KostKu — Dashboard Admin')

@section('header_title', 'Ringkasan Sistem')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card card-teal">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-info">
                <span class="stat-label">Total Pengguna</span>
                <span class="stat-value">1,284</span>
                <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +12% bulan ini</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card card-amber">
            <div class="stat-icon"><i class="bi bi-buildings-fill"></i></div>
            <div class="stat-info">
                <span class="stat-label">Total Properti</span>
                <span class="stat-value">452</span>
                <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> +5 baru</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card card-green">
            <div class="stat-icon"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-info">
                <span class="stat-label">Total Transaksi</span>
                <span class="stat-value">84</span>
                <span class="stat-delta up"><i class="bi bi-arrow-up-short"></i> Rp 124jt</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="stat-card card-rose">
            <div class="stat-icon"><i class="bi bi-shield-check"></i></div>
            <div class="stat-info">
                <span class="stat-label">Verifikasi Pending</span>
                <span class="stat-value">12</span>
                <span class="stat-delta down">Perlu tindakan</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Users -->
    <div class="col-xl-8">
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h6 class="panel-title">Pengguna Terbaru</h6>
                    <p class="panel-sub">Daftar pendaftaran pengguna terakhir</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="see-all-link">Lihat semua</a>
            </div>
            <div class="table-responsive">
                <table class="table tenant-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Tanggal Join</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://i.pravatar.cc/32?img=1" class="tenant-pic" alt=""/>
                                    <div>
                                        <span class="t-name">Andi Wijaya</span>
                                        <span class="t-phone">andi@example.com</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="unit-tag">Penyewa</span></td>
                            <td class="text-muted small">10 Juni 2026</td>
                            <td><span class="status-pill pill-active">Aktif</span></td>
                            <td>
                                <button class="btn-ghost"><i class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://i.pravatar.cc/32?img=2" class="tenant-pic" alt=""/>
                                    <div>
                                        <span class="t-name">Siti Aminah</span>
                                        <span class="t-phone">siti@example.com</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="unit-tag">Pemilik</span></td>
                            <td class="text-muted small">09 Juni 2026</td>
                            <td><span class="status-pill pill-active">Aktif</span></td>
                            <td>
                                <button class="btn-ghost"><i class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://i.pravatar.cc/32?img=4" class="tenant-pic" alt=""/>
                                    <div>
                                        <span class="t-name">Budi Pratama</span>
                                        <span class="t-phone">budi@example.com</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="unit-tag">Penyewa</span></td>
                            <td class="text-muted small">08 Juni 2026</td>
                            <td><span class="status-pill pill-warning">Pending</span></td>
                            <td>
                                <button class="btn-ghost"><i class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="col-xl-4">
        <div class="panel h-100">
            <div class="panel-header">
                <div>
                    <h6 class="panel-title">Status Sistem</h6>
                    <p class="panel-sub">Kesehatan server & layanan</p>
                </div>
                <span class="chip chip-success">Sehat</span>
            </div>
            <div class="maintain-list">
                <div class="maintain-item">
                    <div class="maint-icon" style="background:#e0f2f1;color:#0d9488;"><i class="bi bi-hdd-network-fill"></i></div>
                    <div class="maint-info">
                        <span class="maint-title">Database Server</span>
                        <span class="maint-loc">Uptime: 99.9%</span>
                    </div>
                    <span class="maint-status status-done">Normal</span>
                </div>
                <div class="maintain-item">
                    <div class="maint-icon" style="background:#e0f2f1;color:#0d9488;"><i class="bi bi-cloud-check-fill"></i></div>
                    <div class="maint-info">
                        <span class="maint-title">Storage Service</span>
                        <span class="maint-loc">85% Terpakai</span>
                    </div>
                    <span class="maint-status status-done">Normal</span>
                </div>
                <div class="maintain-item">
                    <div class="maint-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-envelope-fill"></i></div>
                    <div class="maint-info">
                        <span class="maint-title">Email Gateway</span>
                        <span class="maint-loc">Queue: 45</span>
                    </div>
                    <span class="maint-status status-progress">Sibuk</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

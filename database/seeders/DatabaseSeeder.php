<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Property;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RolesSeed::class,
        ]);
        $this->call(AdminSeed::class);

        // Create Owner/Pemilik Users
        User::updateOrCreate(['email' => 'budi@kostku.com'], [
            'name' => 'Budi Santoso',
            'email' => 'budi@kostku.com',
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia',
            'role' => 'tenant',
            'password' => Hash::make('password123'),
        ]);

        User::updateOrCreate(['email' => 'siti@kostku.com'], [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@kostku.com',
            'phone' => '082345678901',
            'address' => 'Bandung, Indonesia',
            'role' => 'tenant',
            'password' => Hash::make('password123'),
        ]);

        // Create Tenant/Penyewa Users
        $tenants = [
            [
                'name' => 'Ahmad Rizaldi',
                'email' => 'ahmad@student.com',
                'phone' => '083456789012',
                'address' => 'Bekasi, Indonesia',
                'role' => 'penyewa',
            ],
            [
                'name' => 'Nur Azizah',
                'email' => 'nur@student.com',
                'phone' => '084567890123',
                'address' => 'Depok, Indonesia',
                'role' => 'penyewa',
            ],
            [
                'name' => 'Randi Kusuma',
                'email' => 'randi@student.com',
                'phone' => '085678901234',
                'address' => 'Tangerang, Indonesia',
                'role' => 'penyewa',
            ],
        ];

        foreach ($tenants as $tenant) {
            User::updateOrCreate(['email' => $tenant['email']], [
                ...$tenant,
                'password' => Hash::make('password123'),
            ]);
        }

        // Run property seeder
        $this->call(PropertySeeder::class);

        $tenant = User::where('email', 'ahmad@student.com')->firstOrFail();
        $property = Property::with('rooms')->firstOrFail();
        $room = $property->rooms->first();

        $activeBooking = Booking::updateOrCreate(
            ['user_id' => $tenant->id, 'kos_name' => $property->name, 'status' => Booking::STATUS_PAID],
            [
                'room_id' => $room->id,
                'room_type' => $room->room_type.' - '.$room->room_number,
                'location' => $property->location,
                'tenant_name' => $tenant->name,
                'booking_date' => now()->subMonth(),
                'check_in_date' => now()->subMonth()->toDateString(),
                'check_out_date' => now()->addMonths(5)->toDateString(),
                'duration_months' => 6,
                'price_per_month' => $room->price_per_month,
                'admin_fee' => 25000,
                'total_amount' => ($room->price_per_month * 6) + 25000,
            ]
        );

        Payment::updateOrCreate(
            ['booking_id' => $activeBooking->id],
            [
                'user_id' => $tenant->id,
                'invoice_number' => 'INV-DEMO-001',
                'order_id' => 'ORDER-DEMO-001',
                'tenant_name' => $tenant->name,
                'gross_amount' => $activeBooking->total_amount,
                'amount' => $activeBooking->total_amount,
                'payment_method' => 'QRIS',
                'payment_status' => Payment::STATUS_PAID,
                'payment_date' => now()->subMonth(),
                'receipt_path' => '-',
                'status' => Payment::STATUS_PAID,
                'paid_at' => now()->subMonth(),
            ]
        );

        foreach (Property::take(2)->get() as $favorite) {
            Wishlist::firstOrCreate(['user_id' => $tenant->id, 'property_id' => $favorite->id]);
        }

        foreach ([
            ['Pembayaran berhasil', 'Pembayaran booking Anda telah dikonfirmasi.', 'payment'],
            ['Booking disetujui', 'Pemilik kos telah menyetujui booking Anda.', 'booking'],
            ['Promo kos terbaru', 'Temukan pilihan kos dengan harga spesial minggu ini.', 'promo'],
        ] as [$title, $message, $type]) {
            Notification::firstOrCreate(
                ['user_id' => $tenant->id, 'title' => $title],
                ['message' => $message, 'type' => $type]
            );
        }
    }
}

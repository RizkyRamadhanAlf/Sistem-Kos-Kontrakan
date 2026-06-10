<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Review;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $owners = User::where('role', 'tenant')->pluck('id')->values();
        $reviewers = User::where('role', 'penyewa')->pluck('id')->values();

        if ($owners->isEmpty() || $reviewers->isEmpty()) {
            return;
        }

        $properties = [
            [
                'name' => 'Kos Nyaman Menteng',
                'location' => 'Jl. Menteng Raya No. 45',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'description' => 'Kos berkualitas dengan fasilitas lengkap, lokasi strategis dekat dengan universitas dan transportasi umum. Cocok untuk mahasiswa dan profesional muda.',
                'image_url' => 'https://via.placeholder.com/500x400',
                'rating' => 4.5,
                'review_count' => 24,
                'facilities' => ['WiFi', 'Kamar Mandi Dalam', 'AC', 'Lemari', 'Meja Belajar'],
                'rules' => ['Tidak boleh membawa tamu lawan jenis setelah jam 8 malam', 'Jam tenang 21.00-06.00', 'Dilarang membuat keributan'],
                'status' => 'active',
                'owner_id' => 2,
            ],
            [
                'name' => 'Kos Eksklusif Bandung',
                'location' => 'Jl. Dago No. 123',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'description' => 'Kos premium dengan desain modern dan minimalis. Setiap kamar dilengkapi dengan furniture berkualitas dan teknologi smart home.',
                'image_url' => 'https://via.placeholder.com/500x400',
                'rating' => 4.8,
                'review_count' => 18,
                'facilities' => ['WiFi Cepat', 'Dapur Bersama', 'Lounge', 'Gym', 'Keamanan 24 Jam'],
                'rules' => ['Jam tenang 22.00-07.00', 'Dilarang merokok di dalam kamar'],
                'status' => 'active',
                'owner_id' => 3,
            ],
            [
                'name' => 'Kos Terjangkau Surabaya',
                'location' => 'Jl. Ahmad Yani No. 67',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'description' => 'Kos dengan harga terjangkau namun tetap nyaman dan bersih. Dekat dengan pasar dan pusat kota.',
                'image_url' => 'https://via.placeholder.com/500x400',
                'rating' => 4.2,
                'review_count' => 15,
                'facilities' => ['WiFi', 'Kamar Mandi Dalam', 'Kulkas', 'Lemari Besar'],
                'rules' => ['Tidak boleh membawa tamu berlebihan', 'Bayar tepat waktu'],
                'status' => 'active',
                'owner_id' => 2,
            ],
            [
                'name' => 'KostKu Living Yogyakarta',
                'location' => 'Jl. Kaliurang KM 5',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'description' => 'Hunian dekat kampus dengan area komunal yang nyaman.',
                'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267',
                'rating' => 4.7,
                'review_count' => 31,
                'facilities' => ['WiFi', 'AC', 'Laundry', 'Dapur Bersama'],
                'rules' => ['Jaga kebersihan area bersama'],
                'status' => 'active',
                'owner_id' => 2,
            ],
            [
                'name' => 'Kos Urban Malang',
                'location' => 'Jl. Soekarno Hatta No. 18',
                'city' => 'Malang',
                'province' => 'Jawa Timur',
                'description' => 'Kos modern dekat pusat kuliner dan transportasi.',
                'image_url' => 'https://images.unsplash.com/photo-1554995207-c18c203602cb',
                'rating' => 4.6,
                'review_count' => 20,
                'facilities' => ['WiFi', 'Parkir', 'CCTV', 'Kamar Mandi Dalam'],
                'rules' => ['Dilarang merokok di kamar'],
                'status' => 'active',
                'owner_id' => 2,
            ],
            [
                'name' => 'Kontrakan Harmoni Depok',
                'location' => 'Jl. Margonda Raya No. 90',
                'city' => 'Depok',
                'province' => 'Jawa Barat',
                'description' => 'Kontrakan nyaman untuk pekerja dan keluarga muda.',
                'image_url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2',
                'rating' => 4.4,
                'review_count' => 16,
                'facilities' => ['Parkir', 'Dapur Pribadi', 'Air Bersih', 'Keamanan'],
                'rules' => ['Bayar tepat waktu'],
                'status' => 'active',
                'owner_id' => 2,
            ],
        ];

        foreach ($properties as $index => $propertyData) {
            unset($propertyData['owner_id']);

            $property = Property::updateOrCreate(['name' => $propertyData['name']], [
                ...$propertyData,
                'owner_id' => $owners[$index % $owners->count()],
            ]);

            // Buat kamar untuk setiap kos
            $rooms = [
                [
                    'room_number' => '101',
                    'room_type' => 'Standar',
                    'capacity' => 1,
                    'price_per_month' => 1500000,
                    'description' => 'Kamar standar dengan ukuran 3x4 meter',
                    'status' => 'available',
                ],
                [
                    'room_number' => '102',
                    'room_type' => 'Standar',
                    'capacity' => 1,
                    'price_per_month' => 1500000,
                    'description' => 'Kamar standar dengan ukuran 3x4 meter',
                    'status' => 'available',
                ],
                [
                    'room_number' => '201',
                    'room_type' => 'Deluxe',
                    'capacity' => 2,
                    'price_per_month' => 2500000,
                    'description' => 'Kamar deluxe dengan balkon',
                    'status' => 'available',
                ],
                [
                    'room_number' => '202',
                    'room_type' => 'Deluxe',
                    'capacity' => 2,
                    'price_per_month' => 2500000,
                    'description' => 'Kamar deluxe dengan balkon',
                    'status' => 'booked',
                ],
            ];

            foreach ($rooms as $roomData) {
                Room::updateOrCreate([
                    'property_id' => $property->id,
                    'room_number' => $roomData['room_number'],
                ], [
                    ...$roomData,
                ]);
            }

            // Buat review untuk setiap kos
            $reviews = [
                [
                    'user_id' => 4,
                    'rating' => 5,
                    'comment' => 'Kos yang sangat bersih dan nyaman. Pemilik sangat responsif terhadap keluhan. Highly recommended!',
                ],
                [
                    'user_id' => 5,
                    'rating' => 4,
                    'comment' => 'Lokasi strategis dan fasilitas lengkap. Hanya saja WiFi kadang lambat di jam-jam tertentu.',
                ],
                [
                    'user_id' => 6,
                    'rating' => 5,
                    'comment' => 'Terbaik! Pemilik baik, tempat bersih, fasilitas lengkap. Saya sangat puas tinggal di sini.',
                ],
            ];

            foreach ($reviews as $reviewIndex => $reviewData) {
                unset($reviewData['user_id']);
                Review::firstOrCreate([
                    'property_id' => $property->id,
                    'user_id' => $reviewers[$reviewIndex % $reviewers->count()],
                ], [
                    ...$reviewData,
                ]);
            }
        }
    }
}

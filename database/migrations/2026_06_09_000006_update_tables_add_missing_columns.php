<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom user_id pada bookings jika belum ada
        if (!Schema::hasColumn('bookings', 'check_in_date')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->date('check_in_date')->nullable()->after('booking_date');
                $table->date('check_out_date')->nullable()->after('check_in_date');
                $table->unsignedBigInteger('room_id')->nullable()->after('user_id');
                
                $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            });
        }

        // Tambah kolom user_id pada payments jika belum ada
        if (!Schema::hasColumn('payments', 'user_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('booking_id');
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // Tambah kolom profile_photo_path pada users jika belum ada
        if (!Schema::hasColumn('users', 'profile_photo_path')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_photo_path')->nullable()->after('password');
            });
        }
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'room_id')) {
                $table->dropForeign(['room_id']);
            }
            if (Schema::hasColumn('bookings', 'check_in_date')) {
                $table->dropColumn('check_in_date');
            }
            if (Schema::hasColumn('bookings', 'check_out_date')) {
                $table->dropColumn('check_out_date');
            }
            if (Schema::hasColumn('bookings', 'room_id')) {
                $table->dropColumn('room_id');
            }
        });

        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'profile_photo_path')) {
                $table->dropColumn('profile_photo_path');
            }
        });
    }
};

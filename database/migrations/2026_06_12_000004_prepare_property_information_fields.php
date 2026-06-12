<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('properties', 'description')) {
            Schema::table('properties', fn (Blueprint $table) => $table->text('description')->nullable());
        }

        if (! Schema::hasColumn('properties', 'facilities')) {
            Schema::table('properties', fn (Blueprint $table) => $table->json('facilities')->nullable());
        }

        if (! Schema::hasColumn('properties', 'rules')) {
            Schema::table('properties', fn (Blueprint $table) => $table->text('rules')->nullable());
        } else {
            Schema::table('properties', fn (Blueprint $table) => $table->text('rules')->nullable()->change());
        }

        DB::table('properties')->whereNotNull('rules')->orderBy('id')->each(function ($property) {
            $rules = json_decode($property->rules, true);

            if (is_array($rules)) {
                DB::table('properties')->where('id', $property->id)->update([
                    'rules' => implode(PHP_EOL, array_map(fn ($rule) => '- '.ltrim(trim($rule), '- '), $rules)),
                ]);
            }
        });

        if (Schema::hasColumn('rooms', 'facilities')) {
            Schema::table('rooms', fn (Blueprint $table) => $table->dropColumn('facilities'));
        }

        if (Schema::hasColumn('rooms', 'rules')) {
            Schema::table('rooms', fn (Blueprint $table) => $table->dropColumn('rules'));
        }
    }

    public function down(): void
    {
        // Kolom informasi properti sudah menjadi bagian dari skema inti aplikasi.
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kost_id')
                ->constrained('kosts')
                ->onDelete('cascade');

            $table->string('nomor_kamar');

            $table->decimal('harga', 12, 2);

            $table->integer('kapasitas')->default(1);

            $table->string('status')->default('tersedia');

            $table->text('fasilitas')->nullable();

            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};

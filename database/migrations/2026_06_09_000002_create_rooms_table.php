<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->string('room_number');
            $table->string('room_type');
            $table->integer('capacity')->default(1);
            $table->decimal('price_per_month', 10, 2);
            $table->string('image_url')->nullable();
            $table->longText('description')->nullable();
            $table->string('status')->default('available');
            $table->timestamps();
            
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->unique(['property_id', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

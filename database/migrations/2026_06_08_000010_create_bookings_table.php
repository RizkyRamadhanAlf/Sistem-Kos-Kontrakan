<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kos_name');
            $table->string('room_type')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('tenant_name')->nullable();
            $table->timestamp('booking_date')->nullable();
            $table->integer('duration_months')->default(1);
            $table->unsignedBigInteger('price_per_month')->default(0);
            $table->unsignedBigInteger('admin_fee')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};

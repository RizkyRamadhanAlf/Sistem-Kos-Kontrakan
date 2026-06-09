<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'booking_id')) {
                $table->unsignedBigInteger('booking_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('payments', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('booking_id');
            }
            if (!Schema::hasColumn('payments', 'order_id')) {
                $table->string('order_id')->nullable()->after('invoice_number');
            }
            if (!Schema::hasColumn('payments', 'gross_amount')) {
                $table->unsignedBigInteger('gross_amount')->nullable()->after('order_id');
            }
            if (!Schema::hasColumn('payments', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('gross_amount');
            }
            if (!Schema::hasColumn('payments', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('payments', 'snap_token')) {
                $table->text('snap_token')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('snap_token');
            }
            if (!Schema::hasColumn('payments', 'expired_at')) {
                $table->timestamp('expired_at')->nullable()->after('paid_at');
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $cols = [
                'booking_id','invoice_number','order_id','gross_amount','payment_method','payment_status','snap_token','paid_at','expired_at'
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('payments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolahs')->onDelete('cascade');
            $table->string('order_id')->unique(); // ID unik untuk Midtrans
            $table->string('status')->default('pending'); // pending, success, settled, failure
            $table->bigInteger('gross_amount');
            $table->string('snap_token')->nullable(); // Token untuk memunculkan pop-up Midtrans
            $table->string('payment_type')->nullable(); // gopay, bank_transfer, dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

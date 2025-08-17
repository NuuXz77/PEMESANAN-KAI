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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->bigIncrements('ID_Pembayaran');
            $table->string('Kode_Pembayaran')->unique();
            $table->unsignedBigInteger('id_booking');
            $table->dateTime('waktu_bayar');
            $table->string('metode_bayar');
            $table->integer('nominal');
            $table->enum('status', ['pending', 'sukses', 'gagal'])->default('pending');
            $table->timestamps();

            $table->foreign('id_booking')->references('ID_Booking')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};

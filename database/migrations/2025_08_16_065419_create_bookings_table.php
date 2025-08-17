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
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('ID_Booking');
            $table->string('Kode_Booking')->unique();
            $table->unsignedBigInteger('id_akun');
            $table->unsignedBigInteger('id_jadwal');
            $table->integer('total_harga');
            $table->enum('status', ['proses', 'sukses', 'batal'])->default('proses');
            $table->timestamps();

            $table->foreign('id_akun')->references('ID_Akun')->on('users')->onDelete('cascade');
            $table->foreign('id_jadwal')->references('ID_Jadwal')->on('jadwals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

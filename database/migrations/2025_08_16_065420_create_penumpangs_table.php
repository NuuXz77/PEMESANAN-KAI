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
        Schema::create('penumpangs', function (Blueprint $table) {
            $table->bigIncrements('ID_Penumpang');
            $table->string('Kode_Penumpang')->unique();
            $table->unsignedBigInteger('id_booking');
            $table->string('nama');
            $table->string('no_telp');
            $table->bigInteger('nik');
            $table->timestamps();

            $table->foreign('id_booking')->references('ID_Booking')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penumpangs');
    }
};

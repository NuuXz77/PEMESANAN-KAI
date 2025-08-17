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
            $table->id('ID_Penumpang');
            $table->string('Kode_Penumpang')->unique();
            $table->foreignId('id_booking')->constrained('bookings', 'ID_Booking');
            $table->string('nama');
            $table->string('no_telp');
            $table->string('nik');
            $table->timestamps();

            // Indexes
            $table->index('id_booking');
            $table->index('Kode_Penumpang');
            $table->index('nik');
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

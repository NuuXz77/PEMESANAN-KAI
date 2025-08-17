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
            $table->id('ID_Booking');
            $table->string('Kode_Booking')->unique();
            $table->foreignId('id_akun')->constrained('users', 'ID_Akun');
            $table->foreignId('id_jadwal')->constrained('jadwals', 'ID_Jadwal');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', ['Proses', 'Sukses', 'Batal'])->default('Sukses');
            $table->timestamps();

            // Indexes
            $table->index('id_akun');
            $table->index('id_jadwal');
            $table->index('Kode_Booking');
            $table->index('status');
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

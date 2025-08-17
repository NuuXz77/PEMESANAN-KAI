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
        Schema::create('keretas', function (Blueprint $table) {
            $table->bigIncrements('ID_Kereta');
            $table->string('Kode_Kereta')->unique();
            $table->string('nama_kereta');
            $table->integer('kapasitas');
            $table->enum('tipe', ['Ekonomi', 'Bisnis', 'Eksekutif']);
            $table->enum('status', ['Aktif', 'Nonaktif']);
            $table->string('foto_kereta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keretas');
    }
};

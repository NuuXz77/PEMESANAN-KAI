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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id('ID_Jadwal');
            $table->string('Kode_Jadwal')->unique();
            $table->foreignId('id_kereta')->constrained('keretas', 'ID_Kereta');
            $table->foreignId('id_rute')->constrained('rutes', 'ID_Rute');
            $table->dateTime('waktu_keberangkatan');
            $table->dateTime('waktu_kedatangan');
            $table->timestamps();

            // Indexes
            $table->index('id_kereta');
            $table->index('id_rute');
            $table->index('waktu_keberangkatan');
            $table->index('waktu_kedatangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};

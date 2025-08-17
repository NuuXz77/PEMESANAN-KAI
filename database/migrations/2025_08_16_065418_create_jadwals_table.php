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
            $table->bigIncrements('ID_Jadwal');
            $table->string('Kode_Jadwal')->unique();
            $table->unsignedBigInteger('id_kereta');
            $table->unsignedBigInteger('id_rute');
            $table->dateTime('waktu_keberangkatan');
            $table->dateTime('waktu_kedatangan');
            $table->timestamps();

            $table->foreign('id_kereta')->references('ID_Kereta')->on('keretas')->onDelete('cascade');
            $table->foreign('id_rute')->references('ID_Rute')->on('rutes')->onDelete('cascade');
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

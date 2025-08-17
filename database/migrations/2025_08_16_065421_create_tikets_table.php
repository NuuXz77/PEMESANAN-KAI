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
        Schema::create('tikets', function (Blueprint $table) {
            $table->bigIncrements('ID_Tiket');
            $table->string('Kode_Tiket')->unique();
            $table->unsignedBigInteger('id_kursi')->nullable();
            $table->unsignedBigInteger('id_penumpang');
            $table->date('tanggal_pesan');
            $table->time('waktu');
            $table->timestamps();

            // Kursi table not defined, so just unsignedBigInteger
            $table->foreign('id_penumpang')->references('ID_Penumpang')->on('penumpangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tikets');
    }
};

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
        Schema::create('rutes', function (Blueprint $table) {
            $table->bigIncrements('ID_Rute');
            $table->string('Kode_Rute')->unique();
            $table->unsignedBigInteger('asal_id');
            $table->unsignedBigInteger('tujuan_id');
            $table->integer('jarak_tempuh');
            $table->time('durasi');
            $table->text('jalur_rute')->nullable();
            $table->timestamps();

            $table->foreign('asal_id')->references('ID_Stasiun')->on('stasiuns')->onDelete('cascade');
            $table->foreign('tujuan_id')->references('ID_Stasiun')->on('stasiuns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutes');
    }
};

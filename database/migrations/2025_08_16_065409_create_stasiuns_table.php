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
        Schema::create('stasiuns', function (Blueprint $table) {
            $table->id('ID_Stasiun');
            $table->string('Kode_Stasiun')->unique();
            $table->string('nama_stasiun');
            $table->string('kota');
            $table->text('alamat');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('foto_stasiun')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('Kode_Stasiun');
            $table->index('nama_stasiun');
            $table->index('kota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stasiuns');
    }
};

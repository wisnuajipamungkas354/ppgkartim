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
        Schema::create('mubaligh_setempats', function (Blueprint $table) {
            $table->id();
            $table->enum('is_menikah', ['SUDAH', 'BELUM'])->default('BELUM');
            $table->integer('jml_tugas');
            $table->string('tahun_tugas');
            $table->boolean('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mubaligh_setempats');
    }
};

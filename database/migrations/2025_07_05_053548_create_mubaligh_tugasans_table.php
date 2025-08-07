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
        Schema::create('mubaligh_tugasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insan_role_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('tingkatan_tugas', ['DAERAH', 'DESA', 'KELOMPOK', 'PONDOK']);
            $table->string('asal_pondok');
            $table->enum('is_menikah', ['SUDAH', 'BELUM'])->default('BELUM');
            $table->integer('tugasan_ke');
            $table->date('tgl_mulai_tugas');
            $table->date('tgl_selesai_tugas')->nullable();
            $table->boolean('selesai_tugas')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mubaligh_tugasans');
    }
};

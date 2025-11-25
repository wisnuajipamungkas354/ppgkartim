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
        Schema::create('mubalighs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insan_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('kategori', ['MT', 'MS']);
            // mt
            $table->enum('tingkatan_tugas', ['DAERAH', 'DESA', 'KELOMPOK', 'PONDOK'])->nullable();
            $table->string('asal_pondok');
            $table->integer('tugasan_ke')->nullable();
            $table->date('tgl_mulai_tugas')->nullable();
            $table->date('tgl_selesai_tugas')->nullable();
            $table->boolean('selesai_tugas')->nullable();
            // ms
            $table->integer('jml_tugas')->nullable();
            $table->string('lama_tugas')->nullable();
            $table->boolean('aktif_mengajar')->nullable();
            $table->string('konfirmasi_kesiapan_tugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mubalighs');
    }
};

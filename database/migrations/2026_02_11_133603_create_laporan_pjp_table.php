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
        Schema::create('laporan_pjps', function (Blueprint $table) {
            $table->id();
            $table->morphs('reportable');
            $table->string('month', 2);
            $table->string('year', 4);
            $table->text('keterangan');
            $table->timestamps();
        });

        Schema::create('laporan_pjp_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_pjp_id')->constrained('laporan_pjps', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('jenis_kegiatan');
            $table->string('nm_kegiatan');
            $table->date('tanggal')->nullable(); 
            $table->string('materi');
            $table->string('peserta');
            $table->integer('jml_terlaksana');
            $table->json('dokumentasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('laporan_pjp_musyawarohs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_pjp_id')->constrained('laporan_pjps', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('judul_musyawaroh');
            $table->date('tanggal');
            $table->json('dokumentasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('laporan_pjp_monitorings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_pjp_id')->constrained('laporan_pjps', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('tingkatan');
            $table->string('label');
            $table->unsignedBigInteger('reference_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_pjp');
        Schema::dropIfExists('laporan_pjp_kegiatan');
        Schema::dropIfExists('laporan_pjp_musyawaroh');
        Schema::dropIfExists('laporan_pjp_monitoring');
    }
};

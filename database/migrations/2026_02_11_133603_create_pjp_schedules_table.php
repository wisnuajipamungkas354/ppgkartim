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
        Schema::create('pjp_schedules', function (Blueprint $table) {
            $table->id();
            $table->morphs('scheduleable'); // Untuk menyimpan siapa yang membuat jadwal (PJP Daerah/Desa)
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->date('deadline_laporan');
            $table->json('musyawaroh_rutin');
            $table->json('kegiatan_rutin');
            $table->enum('status', ['DRAFT', 'DIBUKA', 'DITUTUP'])->default('DRAFT');
            $table->timestamps();
            $table->unique(['scheduleable_type', 'scheduleable_id', 'bulan', 'tahun'], 'pjp_schedule_unique');
        });

        Schema::create('pjp_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pjp_schedule_id')->constrained('pjp_schedules', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->morphs('reportable');
            $table->enum('status', ['BELUM DIISI', 'SELESAI', 'TIDAK LAPORAN'])->default('BELUM DIISI');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['pjp_schedule_id', 'reportable_type', 'reportable_id'], 'pjp_report_unique');
        });

        Schema::create('pjp_kegiatan_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pjp_report_id')->constrained('pjp_reports', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('jenis_kegiatan', ['RUTIN', 'KHUSUS'])->nullable();
            $table->string('nm_kegiatan')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('materi')->nullable();
            $table->string('peserta')->nullable();
            $table->integer('jml_terlaksana')->nullable();
            $table->json('dokumentasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('pjp_musyawaroh_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pjp_report_id')->constrained('pjp_reports', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('judul_musyawaroh')->nullable();
            $table->date('tanggal')->nullable();
            $table->json('dokumentasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pjp_schedules');
        Schema::dropIfExists('pjp_reports');
        Schema::dropIfExists('pjp_kegiatan_reports');
        Schema::dropIfExists('pjp_musyawaroh_reports');
    }
};

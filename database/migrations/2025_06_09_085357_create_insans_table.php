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
        // Menyimpan data individu jamaah
        Schema::create('insans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daerah_id')->constrained('daerahs', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('desa_id')->constrained('desas', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('kelompok_id')->constrained('kelompoks', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->json('url_foto')->nullable();
            $table->string('nama');
            $table->enum('jk', ['L', 'P'])->nullable();
            $table->string('kota_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->enum('gol_dar', ['A', 'B', 'O', 'AB'])->nullable();
            $table->integer('usia')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('pendidikan_terakhir', 15)->nullable();
            // Khusus Pra Nikah Selain Mahasiswa (SD, SMP, SMA/K, D3, S1/D4, S2, S3)
            $table->string('jurusan')->nullable();
            // Status Pernikahan
            $table->enum('perkawinan', ['LAJANG', 'MENIKAH'])->default('LAJANG');
            $table->enum('siap_nikah', ['SIAP', 'BELUM'])->nullable();
            $table->json('detail_siap_nikah')->nullable();
            // Keluarga
            $table->integer('anak_ke')->nullable();
            $table->integer('jml_saudara')->nullable();
            $table->string('nm_ayah')->nullable();
            $table->string('nm_ibu')->nullable();
            $table->string('no_hp_wali', 15)->nullable();
            // Minat Bakat
            $table->json('minat_bakat')->nullable();
            $table->boolean('is_mubaligh')->default(false); // Apakah seorang mubaligh atau bukan (MT/MS)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insans');
        Schema::dropIfExists('dapukans');
    }
};

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
            $table->enum('jk', ['L', 'P']);
            $table->string('kota_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('pendidikan_terakhir', 15)->nullable();
            $table->string('jurusan')->nullable();
            // Khusus Lepas Pelajar Selain Mahasiswa (SD, SMP, SMA/K, D3, S1/D4, S2, S3)
            $table->timestamps();
            $table->softDeletes();
        });

        // Menyimpan list-list peran jamaah
        Schema::create('dapukans', function (Blueprint $table) {
            $table->id();
            $table->string('nm_dapukan');
            $table->timestamps();
        });

        // Menyimpan data peran jamaah
        Schema::create('insan_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insan_id')->constrained('insans', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('dapukan_id')->constrained()->cascadeOnDelete()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insans');
        Schema::dropIfExists('peran_insans');
        Schema::dropIfExists('insan_roles');
    }
};

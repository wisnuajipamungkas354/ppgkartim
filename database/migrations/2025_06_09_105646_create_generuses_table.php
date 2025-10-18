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
        Schema::create('generuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insan_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nis')->nullable();
            $table->enum('jenis_data', ['MM', 'CBRWT']);
            $table->enum('kategori', ['PAUD','CABERAWIT', 'PRA_REMAJA', 'REMAJA', 'PRA_NIKAH']);
            $table->foreignId('kelas_ppg_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('status_id')->nullable()->constrained()->cascadeOnUpdate();
            // PAUD/TK, SD, SMP, SMA/K,
            // Mahasiswa D3, Mahasiswa S1/D4, Mahasiswa S2, Mahasiswa S3, 
            // Pencari Kerja, Karyawan/Pegawai, Wirausaha/Freelance

            $table->json('detail_status')->nullable();
            // SMA/K = Jurusan (IPA, IPS, Sastra/Bahasa, Teknik Komputer dan Jaringan, Rekayasa Perangkat Lunak, Tata Boga, dll)
            // Mahasiswa = Nama Prodi (Sistem Informasi, Teknik Informatika, Sastra Inggris, dll)
            // Pencari Kerja = Keahlian Khusus
            // Karyawan/Pegawai = Jabatan
            // Wirausaha/Freelance = Bidang Usaha
            // Kalau MT akan terisi otomatis dikelompok mana dia tugas

            $table->boolean('aktif_mengajar')->default(false);
            $table->boolean('is_mubaligh')->default(false);
            $table->boolean('is_verified')->default(false);

            // PINDAH SAMBUNG, MENIKAH, MONDOK, MONDOK & SEKOLAH, MENINGGAL, DLL
            $table->string('riwayat_update')->nullable(); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generuses');
    }
};

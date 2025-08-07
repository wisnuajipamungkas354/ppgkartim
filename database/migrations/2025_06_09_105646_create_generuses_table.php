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
            $table->foreignId('insan_role_id')->constrained('insan_roles', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nis')->nullable();
            $table->enum('jenis_data', ['MM', 'CBRWT']);
            $table->enum('kategori', ['PAUD','CABERAWIT', 'PRA_REMAJA', 'REMAJA', 'PRA_NIKAH']);
            $table->enum('gol_dar', ['A', 'B', 'O', 'AB'])->nullable();
            $table->foreignId('kelas_ppg_id')->nullable()->constrained('kelas_ppgs', 'id')->nullOnDelete();

            $table->foreignId('status_id')->nullable()->constrained()->cascadeOnUpdate();
            // PAUD/TK, SD, SMP, SMA/K,
            // Mahasiswa D3, Mahasiswa S1/D4, Mahasiswa S2, Mahasiswa S3, 
            // Pencari Kerja, Karyawan/Pegawai, Wirausaha/Freelance
            // Kalau MT akan terisi otomatis

            $table->json('detail_status')->nullable(); 
            // SMA/K = Jurusan (IPA, IPS, Sastra/Bahasa, Teknik Komputer dan Jaringan, Rekayasa Perangkat Lunak, Tata Boga, dll)
            // Mahasiswa = Nama Prodi (Sistem Informasi, Teknik Informatika, Sastra Inggris, dll)
            // Pencari Kerja = Keahlian Khusus
            // Karyawan/Pegawai = Jabatan
            // Wirausaha/Freelance = Bidang Usaha
            // Kalau MT akan terisi otomatis dikelompok mana dia tugas

            $table->string('nm_ayah')->nullable(); // Nama ayah
            $table->string('nm_ibu')->nullable(); // Nama ibu
            $table->string('no_hp_wali', 15)->nullable();
            $table->foreignId('minat_id')->nullable()->constrained();
            $table->string('detail_minat')->nullable();
            $table->enum('siap_nikah', ['SIAP', 'BELUM'])->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('riwayat_update')->nullable(); // PINDAH SAMBUNG KAH, MENIKAH KAH, MONDOK KAH, MENINGGAL KAH, DLL
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

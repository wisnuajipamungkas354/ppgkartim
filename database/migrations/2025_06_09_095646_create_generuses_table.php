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
        // - jenis data (mm/caberawit)
        // - desa
        // - kelompok
        // - nama lengkap
        // - jk
        // - kota lahir
        // - tgl lahir
        // - kelas di sekolah
        // - kelas di PPG
        // - status
        // - detail status
        // - nama orang tua
        // - nomor WA orang tua
        // - nomor WA pribadi
        // - minat/hobi (
        // - gol_dar (opsional)
        // - siap_nikah
        // - is_active
        Schema::create('generuses', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_data', ['MM', 'CBRWT']);
            $table->enum('kategori', ['PAUD','CABERAWIT', 'PRA REMAJA', 'REMAJA', 'PRA NIKAH']);
            $table->foreignId('desa_id')->constrained('desas', 'id');
            $table->foreignId('kelompok_id')->constrained('kelompoks', 'id');
            $table->json('url_foto')->nullable();
            $table->string('nama');
            $table->enum('jk', ['L', 'P']);
            $table->string('kota_lahir');
            $table->date('tgl_lahir');
            $table->enum('gol_dar', ['A', 'B', 'O', 'AB'])->nullable();
            $table->enum('mubaligh', ['MT', 'MS', 'BUKAN'])->default('BUKAN');
            $table->string('kelas_di_ppg'); // Kemungkinan Relasi ke tabel kelas PPG

            $table->string('status')->nullable(); 
            // PAUD/TK, SD, SMP, SMA/K, 
            // Mahasiswa D3, Mahasiswa S1/D4, Mahasiswa S2, Mahasiswa S3, 
            // Pencari Kerja, Karyawan/Pegawai, Wirausaha/Freelance

            $table->integer('kelas_di_sekolah')->nullable();
            // SD-SMA/K (Kelas 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12) 

            $table->string('pendidikan_terakhir')->nullable();
            // Khusus Lepas Pelajar Selain Mahasiswa = SD, SMP, SMA/K, D3, S1/D4, S2, S3

            $table->string('detail_status')->nullable(); 
            // SMA/K = Jurusan (IPA, IPS, Sastra/Bahasa, Teknik Komputer dan Jaringan, Rekayasa Perangkat Lunak, Tata Boga, dll)
            // Mahasiswa = Nama Prodi (Sistem Informasi, Teknik Informatika, Sastra Inggris, dll)
            // Pencari Kerja = Keahlian Khusus
            // Karyawan/Pegawai = Jabatan
            // Wirausaha/Freelance = Bidang Usaha

            $table->string('nm_wali'); // Nama orang tua atau wali
            $table->string('no_hp_wali');
            $table->string('no_hp_pribadi')->nullable();
            $table->string('minat');
            $table->enum('siap_nikah', ['SIAP', 'BELUM']);
            $table->boolean('is_active');
            $table->enum('riwayat_is_active', ['PERGI MONDOK', 'DATA BARU', 'PINDAH SAMBUNG LUAR DAERAH', 'PINDAH SAMBUNG DALAM DAERAH', 'SAMBANG', 'PULANG MONDOK', 'PULANG TUGAS', 'MUALAF'])->default('DATA BARU');
            $table->timestamps();
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

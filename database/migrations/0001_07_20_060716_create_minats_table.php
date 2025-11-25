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
        Schema::create('minats', function (Blueprint $table) {
            $table->id();
            $table->string('nm_minat', 150);
            $table->string('slug', 100);
            $table->string('kategori', 150)->nullable();
            $table->string('slug_kategori', 100)->nullable();
            $table->timestamps();
        });

        /** * Tentang Minat & Bakat
         * Dalam aplikasi ini, minat dan bakat dikategorikan menjadi 8 bidang utama untuk memetakan potensi Generus secara komprehensif:
         *
         * 1. KREATIVITAS & SENI
         * Fokus pada ekspresi diri, imajinasi, dan penciptaan karya visual, audio, atau performatif.
         * Contoh: Melukis, Bermain Musik, Menulis Fiksi/Puisi, Desain, Akting.
         *
         * 2. OLAHRAGA & FISIK
         * Fokus pada gerakan tubuh, kebugaran, dan penguasaan keterampilan motorik dalam konteks kompetisi atau kesehatan.
         * Contoh: Sepak Bola, Basket, Berenang, Yoga, Seni Bela Diri, Gym/Angkat Beban.
         *
         * 3. PENGETAHUAN & INTELEKTUAL
         * Fokus pada perluasan wawasan, kemampuan analisis, dan penguasaan ilmu pengetahuan atau bahasa baru.
         * Contoh: Riset Sejarah/Sains, Membaca Serius, Belajar Bahasa Asing, Filosofi, Pecahkan Teka-Teki.
         *
         * 4. ALAM & PETUALANGAN
         * Fokus pada eksplorasi lingkungan luar, kegiatan di alam terbuka, dan konservasi alam.
         * Contoh: Hiking, Camping, Berkebun, Konservasi Lingkungan, Fotografi Alam.
         *
         * 5. KOLEKSI & REKREASI (HOBI SPESIFIK)
         * Fokus pada pengumpulan benda tertentu yang terstruktur atau hobi permainan/rekreasi yang memiliki aturan jelas.
         * Contoh: Mengumpulkan Koin/Perangko/Figur, Bermain Video Game, Board Game, Merakit Model Kit.
         *
         * 6. TEKNOLOGI & DIGITAL
         * Fokus pada penguasaan alat digital, pengembangan sistem, dan inovasi berbasis teknologi.
         * Contoh: Coding/Pemrograman, Desain Website, Editing Video, Pengembangan Aplikasi, Robotika.
         *
         * 7. KULINER & GAYA HIDUP
         * Fokus pada minat seputar makanan, minuman, dan aspek estetika yang berhubungan dengan penampilan diri atau penataan lingkungan.
         * Contoh: Memasak, Membuat Kue, Fashion, Makeup Artistry, Dekorasi Rumah.
         *
         * 8. SOSIAL & KOMUNITAS
         * Fokus pada interaksi dengan orang lain, kepemimpinan, kolaborasi, dan kegiatan pelayanan/pengabdian masyarakat.
         * Contoh: Sukarelawan, Organisasi Sosial, Debat, Public Speaking, Mentoring.
         * 
         * Catatan: Satu minat/bakat dapat dikaitkan dengan lebih dari satu kategori (Contoh: Menari bisa masuk Kreativitas & Seni, dan Olahraga & Fisik).
         */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minats');
    }
};

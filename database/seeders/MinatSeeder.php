<?php

namespace Database\Seeders;

use App\Models\Minat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MinatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Minat::insert([
            // 1. KREATIVITAS & SENI
            [
                'id' => 1,
                'nm_minat' => 'Menggambar',
                'slug' => Str::slug('Menggambar'),
                'kategori' => 'Kreativitas & Seni',
                'slug_kategori' => Str::slug('Kreativitas & Seni'),
            ],
            [
                'id' => 2,
                'nm_minat' => 'Desain Grafis',
                'slug' => Str::slug('Desain Grafis'),
                'kategori' => 'Kreativitas & Seni',
                'slug_kategori' => Str::slug('Kreativitas & Seni'),
            ],
            [
                'id' => 3,
                'nm_minat' => 'Menulis Cerita',
                'slug' => Str::slug('Menulis Cerita'),
                'kategori' => 'Kreativitas & Seni',
                'slug_kategori' => Str::slug('Kreativitas & Seni'),
            ],
            [
                'id' => 4,
                'nm_minat' => 'Fotografi / Videografi',
                'slug' => Str::slug('Fotografi / Videografi'),
                'kategori' => 'Kreativitas & Seni',
                'slug_kategori' => Str::slug('Kreativitas & Seni'),
            ],
            [
                'id' => 6,
                'nm_minat' => 'Menyanyi',
                'slug' => Str::slug('Menyanyi'),
                'kategori' => 'Kreativitas & Seni',
                'slug_kategori' => Str::slug('Kreativitas & Seni'),
            ],
            [
                'id' => 7,
                'nm_minat' => 'Menari',
                'slug' => Str::slug('Menari'),
                'kategori' => 'Kreativitas & Seni',
                'slug_kategori' => Str::slug('Kreativitas & Seni'),
            ],
        
            // 2. OLAHRAGA & FISIK
            [
                'id' => 8,
                'nm_minat' => 'Sepak Bola',
                'slug' => Str::slug('Sepak Bola'),
                'kategori' => 'Olahraga & Fisik',
                'slug_kategori' => Str::slug('Olahraga & Fisik'),
            ],
            [
                'id' => 9,
                'nm_minat' => 'Badminton',
                'slug' => Str::slug('Badminton'),
                'kategori' => 'Olahraga & Fisik',
                'slug_kategori' => Str::slug('Olahraga & Fisik'),
            ],
            [
                'id' => 10,
                'nm_minat' => 'Basket',
                'slug' => Str::slug('Basket'),
                'kategori' => 'Olahraga & Fisik',
                'slug_kategori' => Str::slug('Olahraga & Fisik'),
            ],
            [
                'id' => 11,
                'nm_minat' => 'Voli',
                'slug' => Str::slug('Voli'),
                'kategori' => 'Olahraga & Fisik',
                'slug_kategori' => Str::slug('Olahraga & Fisik'),
            ],
            [
                'id' => 12,
                'nm_minat' => 'Renang',
                'slug' => Str::slug('Renang'),
                'kategori' => 'Olahraga & Fisik',
                'slug_kategori' => Str::slug('Olahraga & Fisik'),
            ],
            [
                'id' => 13,
                'nm_minat' => 'Lari/Jogging',
                'slug' => Str::slug('Lari Jogging'),
                'kategori' => 'Olahraga & Fisik',
                'slug_kategori' => Str::slug('Olahraga & Fisik'),
            ],
            [
                'id' => 14,
                'nm_minat' => 'Pencak Silat)',
                'slug' => Str::slug('Pencak Silat'),
                'kategori' => 'Olahraga & Fisik',
                'slug_kategori' => Str::slug('Olahraga & Fisik'),
            ],
        
            // 3. PENGETAHUAN & INTELEKTUAL
            [
                'id' => 15,
                'nm_minat' => 'Membaca Buku',
                'slug' => Str::slug('Membaca Buku'),
                'kategori' => 'Pengetahuan & Intelektual',
                'slug_kategori' => Str::slug('Pengetahuan & Intelektual'),
            ],
            [
                'id' => 16,
                'nm_minat' => 'Menulis Artikel',
                'slug' => Str::slug('Menulis Artikel'),
                'kategori' => 'Pengetahuan & Intelektual',
                'slug_kategori' => Str::slug('Pengetahuan & Intelektual'),
            ],
            [
                'id' => 17,
                'nm_minat' => 'Riset Sains',
                'slug' => Str::slug('Riset Sains'),
                'kategori' => 'Pengetahuan & Intelektual',
                'slug_kategori' => Str::slug('Pengetahuan & Intelektual'),
            ],
            [
                'id' => 18,
                'nm_minat' => 'Belajar Bahasa Asing',
                'slug' => Str::slug('Belajar Bahasa Asing'),
                'kategori' => 'Pengetahuan & Intelektual',
                'slug_kategori' => Str::slug('Pengetahuan & Intelektual'),
            ],
            [
                'id' => 19,
                'nm_minat' => 'Matematika / Logika',
                'slug' => Str::slug('Matematika Logika'),
                'kategori' => 'Pengetahuan & Intelektual',
                'slug_kategori' => Str::slug('Pengetahuan & Intelektual'),
            ],
            [
                'id' => 20,
                'nm_minat' => 'Puzzle / Rubik',
                'slug' => Str::slug('Puzzle Rubik'),
                'kategori' => 'Pengetahuan & Intelektual',
                'slug_kategori' => Str::slug('Pengetahuan & Intelektual'),
            ],
        
            // 4. ALAM & PETUALANGAN
            [
                'id' => 21,
                'nm_minat' => 'Hiking',
                'slug' => Str::slug('Hiking'),
                'kategori' => 'Alam & Petualangan',
                'slug_kategori' => Str::slug('Alam & Petualangan'),
            ],
            [
                'id' => 22,
                'nm_minat' => 'Camping',
                'slug' => Str::slug('Camping'),
                'kategori' => 'Alam & Petualangan',
                'slug_kategori' => Str::slug('Alam & Petualangan'),
            ],
            [
                'id' => 23,
                'nm_minat' => 'Berkebun',
                'slug' => Str::slug('Berkebun'),
                'kategori' => 'Alam & Petualangan',
                'slug_kategori' => Str::slug('Alam & Petualangan'),
            ],
            [
                'id' => 25,
                'nm_minat' => 'Memancing',
                'slug' => Str::slug('Memancing'),
                'kategori' => 'Alam & Petualangan',
                'slug_kategori' => Str::slug('Alam & Petualangan'),
            ],
        
            // 5. KOLEKSI & REKREASI
            [
                'id' => 26,
                'nm_minat' => 'Mengoleksi Koin/Perangko',
                'slug' => Str::slug('Mengoleksi Koin Perangko'),
                'kategori' => 'Koleksi & Rekreasi',
                'slug_kategori' => Str::slug('Koleksi & Rekreasi'),
            ],
            [
                'id' => 27,
                'nm_minat' => 'Action Figure / Model Kit',
                'slug' => Str::slug('Action Figure Model Kit'),
                'kategori' => 'Koleksi & Rekreasi',
                'slug_kategori' => Str::slug('Koleksi & Rekreasi'),
            ],
            [
                'id' => 28,
                'nm_minat' => 'Video Game',
                'slug' => Str::slug('Video Game'),
                'kategori' => 'Koleksi & Rekreasi',
                'slug_kategori' => Str::slug('Koleksi & Rekreasi'),
            ],
            [
                'id' => 30,
                'nm_minat' => 'Merakit Gundam',
                'slug' => Str::slug('Merakit Gundam'),
                'kategori' => 'Koleksi & Rekreasi',
                'slug_kategori' => Str::slug('Koleksi & Rekreasi'),
            ],
        
            // 6. TEKNOLOGI & DIGITAL
            [
                'id' => 31,
                'nm_minat' => 'Coding / Pemrograman',
                'slug' => Str::slug('Coding Pemrograman'),
                'kategori' => 'Teknologi & Digital',
                'slug_kategori' => Str::slug('Teknologi & Digital'),
            ],
            [
                'id' => 32,
                'nm_minat' => 'Internet of Things (IoT)',
                'slug' => Str::slug('Internet of Things (IoT)'),
                'kategori' => 'Teknologi & Digital',
                'slug_kategori' => Str::slug('Teknologi & Digital'),
            ],
            [
                'id' => 33,
                'nm_minat' => 'Editing Foto & Video',
                'slug' => Str::slug('Editing Foto & Video'),
                'kategori' => 'Teknologi & Digital',
                'slug_kategori' => Str::slug('Teknologi & Digital'),
            ],
            [
                'id' => 35,
                'nm_minat' => 'Animasi 2D/3D',
                'slug' => Str::slug('Animasi 2D 3D'),
                'kategori' => 'Teknologi & Digital',
                'slug_kategori' => Str::slug('Teknologi & Digital'),
            ],
            [
                'id' => 36,
                'nm_minat' => 'Robotika',
                'slug' => Str::slug('Robotika'),
                'kategori' => 'Teknologi & Digital',
                'slug_kategori' => Str::slug('Teknologi & Digital'),
            ],
        
            // 7. KULINER & GAYA HIDUP
            [
                'id' => 37,
                'nm_minat' => 'Memasak',
                'slug' => Str::slug('Memasak'),
                'kategori' => 'Kulinier & Gaya Hidup',
                'slug_kategori' => Str::slug('Kulinier & Gaya Hidup'),
            ],
            [
                'id' => 38,
                'nm_minat' => 'Membuat Kue',
                'slug' => Str::slug('Membuat Kue'),
                'kategori' => 'Kulinier & Gaya Hidup',
                'slug_kategori' => Str::slug('Kulinier & Gaya Hidup'),
            ],
            [
                'id' => 39,
                'nm_minat' => 'Fashion Style',
                'slug' => Str::slug('Fashion Style'),
                'kategori' => 'Kulinier & Gaya Hidup',
                'slug_kategori' => Str::slug('Kulinier & Gaya Hidup'),
            ],
            [
                'id' => 40,
                'nm_minat' => 'Makeup Artistry',
                'slug' => Str::slug('Makeup Artistry'),
                'kategori' => 'Kulinier & Gaya Hidup',
                'slug_kategori' => Str::slug('Kulinier & Gaya Hidup'),
            ],
            [
                'id' => 41,
                'nm_minat' => 'Dekorasi Rumah',
                'slug' => Str::slug('Dekorasi Rumah'),
                'kategori' => 'Kulinier & Gaya Hidup',
                'slug_kategori' => Str::slug('Kulinier & Gaya Hidup'),
            ],
        
            // 8. SOSIAL & KOMUNITAS
            [
                'id' => 42,
                'nm_minat' => 'Public Speaking',
                'slug' => Str::slug('Public Speaking'),
                'kategori' => 'Sosial & Komunitas',
                'slug_kategori' => Str::slug('Sosial & Komunitas'),
            ],
            [
                'id' => 43,
                'nm_minat' => 'Debat',
                'slug' => Str::slug('Debat'),
                'kategori' => 'Sosial & Komunitas',
                'slug_kategori' => Str::slug('Sosial & Komunitas'),
            ],
            [
                'id' => 44,
                'nm_minat' => 'Berorganisasi',
                'slug' => Str::slug('Berorganisasi'),
                'kategori' => 'Sosial & Komunitas',
                'slug_kategori' => Str::slug('Sosial & Komunitas'),
            ],
            [
                'id' => 45,
                'nm_minat' => 'Volunteer / Sosial',
                'slug' => Str::slug('Volunteer Sosial'),
                'kategori' => 'Sosial & Komunitas',
                'slug_kategori' => Str::slug('Sosial & Komunitas'),
            ],
            [
                'id' => 46,
                'nm_minat' => 'Mentoring / Mengajar',
                'slug' => Str::slug('Mentoring Mengajar'),
                'kategori' => 'Sosial & Komunitas',
                'slug_kategori' => Str::slug('Sosial & Komunitas'),
            ],
        ]);
    }
}

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
            [
                'nm_minat' => 'Kreativitas & Seni',
                'slug' => Str::slug('Kreativitas & Seni'),
            ],
            [
                'nm_minat' => 'Olahraga & Aktivitas Fisik',
                'slug' => Str::slug('Olahraga & Aktivitas Fisik'),
            ],
            [
                'nm_minat' => 'Pengetahuan & Pembelajaran',
                'slug' => Str::slug('Pengetahuan & Pembelajaran'),
            ],
            [
                'nm_minat' => 'Alam & Petualangan',
                'slug' => Str::slug('Alam & Petualangan'),
            ],
            [
                'nm_minat' => 'Koleksi & Hobi Spesifik',
                'slug' => Str::slug('Koleksi & Hobi Spesifik'),
            ],
            [
                'nm_minat' => 'Teknologi & Digital',
                'slug' => Str::slug('Teknologi & Digital'),
            ],
            [
                'nm_minat' => 'Kuliner & Gaya Hidup',
                'slug' => Str::slug('Kuliner & Gaya Hidup'),
            ],
            [
                'nm_minat' => 'Sosial & Komunitas',
                'slug' => Str::slug('Sosial & Komunitas'),
            ],
        ]);
    }
}

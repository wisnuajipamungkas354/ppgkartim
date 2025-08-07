<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Status
        Status::insert([
            ['nm_status' => 'Paud/TK', 'slug' => Str::slug('Paud/TK'), 'placeholder' => 'Masukkan nama Paud/TK'],
            ['nm_status' => 'SD', 'slug' => Str::slug('SD'), 'placeholder' => 'Masukkan nama sekolah SD'],
            ['nm_status' => 'SMP', 'slug' => Str::slug('SMP'), 'placeholder' => 'Masukkan nama sekolah SMP'],
            ['nm_status' => 'SMA/K', 'slug' => Str::slug('SMA SMK'), 'placeholder' => 'Masukkan nama sekolah SMA/K'],
            ['nm_status' => 'Mahasiswa D3', 'slug' => Str::slug('D3'), 'placeholder' => 'Masukkan nama program studi/kampus'],
            ['nm_status' => 'Mahasiswa S1/D4', 'slug' => Str::slug('S1 D4'), 'placeholder' => ''],
            ['nm_status' => 'Mahasiswa S2', 'slug' => Str::slug('S2'), 'placeholder' => ''],
            ['nm_status' => 'Mahasiswa S3', 'slug' => Str::slug('S3'), 'placeholder' => ''],
            ['nm_status' => 'Mubaligh Tugasan (MT)', 'slug' => Str::slug('MT'), 'placeholder' => ''],
            ['nm_status' => 'Pencari Kerja', 'slug' => Str::slug('Pencari Kerja'), 'placeholder' => ''],
            ['nm_status' => 'Karyawan/Pegawai', 'slug' => Str::slug('Karyawan Pegawai'), 'placeholder' => ''],
            ['nm_status' => 'Kuliah Kerja', 'slug' => Str::slug('Kuliah Kerja'), 'placeholder' => ''],
            ['nm_status' => 'Tenaga Sabilillah (SB)', 'slug' => Str::slug('SB'), 'placeholder' => ''],
            ['nm_status' => 'Wirausaha/Freelance', 'slug' => Str::slug('Wirausaha Freelance'), 'placeholder' => ''],
        ]);
    }
}

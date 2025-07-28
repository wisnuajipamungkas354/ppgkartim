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
            ['nm_status' => 'Paud/TK', 'slug' => Str::slug('Paud/TK')],
            ['nm_status' => 'SD', 'slug' => Str::slug('SD')],
            ['nm_status' => 'SMP', 'slug' => Str::slug('SMP')],
            ['nm_status' => 'SMA/K', 'slug' => Str::slug('SMA SMK')],
            ['nm_status' => 'Mahasiswa D3', 'slug' => Str::slug('D3')],
            ['nm_status' => 'Mahasiswa S1/D4', 'slug' => Str::slug('S1 D4')],
            ['nm_status' => 'Mahasiswa S2', 'slug' => Str::slug('S2')],
            ['nm_status' => 'Mahasiswa S3', 'slug' => Str::slug('S3')],
            ['nm_status' => 'Mubaligh Tugasan (MT)', 'slug' => Str::slug('MT')],
            ['nm_status' => 'Pencari Kerja', 'slug' => Str::slug('Pencari Kerja')],
            ['nm_status' => 'Karyawan/Pegawai', 'slug' => Str::slug('Karyawan Pegawai')],
            ['nm_status' => 'Kuliah Kerja', 'slug' => Str::slug('Kuliah Kerja')],
            ['nm_status' => 'Tenaga Sabilillah (SB)', 'slug' => Str::slug('SB')],
            ['nm_status' => 'Wirausaha/Freelance', 'slug' => Str::slug('Wirausaha Freelance')],
        ]);
    }
}

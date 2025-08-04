<?php

namespace Database\Seeders;

use App\Models\KelasPpg;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasPpgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KelasPpg::insert([
            ['nm_kelas' => 'Paud/TK'],
            ['nm_kelas' => 1],
            ['nm_kelas' => 2],
            ['nm_kelas' => 3],
            ['nm_kelas' => 4],
            ['nm_kelas' => 5],
            ['nm_kelas' => 6],
            ['nm_kelas' => 7],
            ['nm_kelas' => 8],
            ['nm_kelas' => 9],
            ['nm_kelas' => 10],
            ['nm_kelas' => 11],
            ['nm_kelas' => 12],
            ['nm_kelas' => 'Pra Nikah 1'],
            ['nm_kelas' => 'Pra Nikah 2'],
            ['nm_kelas' => 'Pra Nikah 3'],
            ['nm_kelas' => 'Pra Nikah 4'],
        ]);
    }
}

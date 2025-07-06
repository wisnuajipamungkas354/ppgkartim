<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Kelompok;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $desas = ['KLARI', 'LEMAH MULYA', 'ADIARSA TIMUR', 'TUNGGAK JATI'];
        foreach($desas as $d) {
            Desa::create(['nm_desa' => $d]);
        }

        $kelompokKlari = ['CIREJAG', 'BELENDUNG', 'CIBALONGSARI 1', 'CIBALONGSARI 2', 'PANCAWATI', 'KALIMULYA'];
        $kelompokLm = ['TAMIANG 1', 'TAMIANG 2', 'CKM', 'TIPAR', 'HNH', 'ANGGADITA'];
        $kelompokAtm = ['WIRASABA', 'BABAKAN JATI', 'KARAWANG KOTA 1', 'KARAWANG KOTA 2', 'WADAS'];
        $kelompokTj = ['TANJUNG BARU BARAT', 'TANJUNG BARU TIMUR', 'TUNGGAK JATI', 'KALANGSURIA', 'PAKIS JAYA'];

        // Kelompok-kelompok Desa Klari
        foreach($kelompokKlari as $kelompok) {
            $desaId = Desa::query()->where('nm_desa', 'KLARI')->value('id');
            Kelompok::create([
                'desa_id' => $desaId,
                'nm_kelompok' => $kelompok,
            ]);
        }

        // Kelompok-kelompok Desa Lemah Mulya
        foreach($kelompokLm as $kelompok) {
            $desaId = Desa::query()->where('nm_desa', 'LEMAH MULYA')->value('id');
            Kelompok::create([
                'desa_id' => $desaId,
                'nm_kelompok' => $kelompok,
            ]);
        }

        // Kelompok-kelompok Desa Adiarsa Timur
        foreach($kelompokAtm as $kelompok) {
            $desaId = Desa::query()->where('nm_desa', 'ADIARSA TIMUR')->value('id');
            Kelompok::create([
                'desa_id' => $desaId,
                'nm_kelompok' => $kelompok,
            ]);
        }

        // Kelompok-kelompok Desa Tunggak Jati
        foreach($kelompokTj as $kelompok) {
            $desaId = Desa::query()->where('nm_desa', 'TUNGGAK JATI')->value('id');
            Kelompok::create([
                'desa_id' => $desaId,
                'nm_kelompok' => $kelompok,
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Insan;
use App\Models\InsanRole;
use App\Models\Kelompok;
use App\Models\PeranInsan;
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
            $isDesa = $kelompok === 'BELENDUNG';
            Kelompok::create([
                'desa_id' => $desaId,
                'nm_kelompok' => $kelompok,
                'is_desa' => $isDesa,
            ]);
        }

        // Kelompok-kelompok Desa Lemah Mulya
        foreach($kelompokLm as $kelompok) {
            $desaId = Desa::query()->where('nm_desa', 'LEMAH MULYA')->value('id');
            $isDesa = $kelompok === 'TAMIANG 1';
            Kelompok::create([
                'desa_id' => $desaId,
                'nm_kelompok' => $kelompok,
                'is_desa' => $isDesa,
            ]);
        }

        // Kelompok-kelompok Desa Adiarsa Timur
        foreach($kelompokAtm as $kelompok) {
            $desaId = Desa::query()->where('nm_desa', 'ADIARSA TIMUR')->value('id');
            $isDesa = $kelompok === 'BABAKAN JATI';
            Kelompok::create([
                'desa_id' => $desaId,
                'nm_kelompok' => $kelompok,
                'is_desa' => $isDesa,
            ]);
        }

        // Kelompok-kelompok Desa Tunggak Jati
        foreach($kelompokTj as $kelompok) {
            $desaId = Desa::query()->where('nm_desa', 'TUNGGAK JATI')->value('id');
            $isDesa = $kelompok === 'KALANGSURIA';
            Kelompok::create([
                'desa_id' => $desaId,
                'nm_kelompok' => $kelompok,
                'is_desa' => $isDesa,
            ]);
        }

        // Peran Insan
        $peranInsan = ['GENERUS', 'MUBALIGH TUGASAN', 'MUBALIGH SETEMPAT'];
        foreach($peranInsan as $peran) {
            PeranInsan::create([
                'nm_peran' => $peran
            ]);
        }

        $insanSatu = [
            'desa_id' => 1,
            'kelompok_id' => 1,
            'nama' => 'Wisnu Aji Pamungkas',
            'jk' => 'L',
            'kota_lahir' => 'Brebes',
            'tgl_lahir' => '2002-03-05',
            'no_hp' => '085889634432',
            'pendidikan_terakhir' => 'SMA/K',
        ];

        $insan = Insan::create($insanSatu);
        InsanRole::create([
            'insan_id' => $insan->id,
            'peran_insan_id' => 1,
        ]);
    }
}

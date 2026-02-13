<?php

namespace Database\Seeders;

use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\Insan;
use App\Models\Kelompok;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $daerah = Daerah::create([
            'nm_daerah' => 'Karawang Timur',
        ]);

        $desas = ['KLARI', 'LEMAH MULYA', 'ADIARSA TIMUR', 'TUNGGAK JATI'];
        foreach($desas as $desa) {
            Desa::create([
                'daerah_id' => $daerah->id,
                'nm_desa' => $desa
            ]);
        }

        $kelompokKlari = ['CIREJAG', 'BELENDUNG', 'CIBALONGSARI 1', 'CIBALONGSARI 2', 'PANCAWATI', 'KALIMULYA'];
        $kelompokLm = ['TAMIANG 1', 'TAMIANG 2', 'CKM 1', 'CKM 2', 'TIPAR', 'HNH', 'ANGGADITA', 'KARAWANG MEGAH'];
        $kelompokAtm = ['WIRASABA', 'BABAKAN JATI 1', 'BABAKAN JATI 2', 'KARAWANG KOTA 1', 'KARAWANG KOTA 2', 'WADAS'];
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
            $isDaerah = $kelompok === 'TAMIANG 1';
            Kelompok::create([
                'desa_id' => $desaId,
                'nm_kelompok' => $kelompok,
                'is_desa' => $isDesa,
                'is_daerah' => $isDaerah,
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

        // Status
        $this->call([
            StatusSeeder::class,
            MinatSeeder::class,
            KelasPpgSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        // Individu / Insan
        // $insanSatu = [
        //     'daerah_id' => 1,
        //     'desa_id' => 1,
        //     'kelompok_id' => 1,
        //     'nama' => 'Wisnu Aji Pamungkas',
        //     'jk' => 'L',
        //     'kota_lahir' => 'Brebes',
        //     'tgl_lahir' => '2002-03-05',
        //     'gol_dar' => 'A',
        //     'usia' => 23,
        //     'no_hp' => '+6285889634432',
        //     'pendidikan_terakhir' => 'sma-smk',
        //     'jurusan' => 'Teknik Komputer & Jaringan',
        //     'perkawinan' => 'LAJANG',
        //     'dapukan' => [Str::slug('GENERUS')],
        //     'siap_nikah' => 'BELUM',
        //     'nm_ayah' => 'Sutarso',
        //     'nm_ibu' => 'Nuning Handayani',
        //     'minat_id' => 6,
        //     'detail_minat' => 'Ngoding & Servis Hardware',
        // ];

        // $insan = Insan::create($insanSatu);

        // Generus::create([
        //     'insan_id' => $insan->id,
        //     'jenis_data' => 'MM',
        //     'kategori' => 'PRA_NIKAH',
        //     'kelas_ppg_id' => 14,
        //     'status_id' => 6,
        //     'detail_status' => [
        //         'program_studi' => 'Sistem Informasi',
        //         'universitas' => 'Universitas Bina Sarana Informatika Cikarang'
        //     ],
        // ]);
    }
}

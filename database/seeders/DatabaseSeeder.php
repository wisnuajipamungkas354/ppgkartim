<?php

namespace Database\Seeders;

use App\Models\Daerah;
use App\Models\Dapukan;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\Insan;
use App\Models\InsanRole;
use App\Models\Kelompok;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        foreach($desas as $d) {
            Desa::create([
                'daerah_id' => $daerah->id,
                'nm_desa' => $d
            ]);
        }

        $kelompokKlari = ['CIREJAG', 'BELENDUNG', 'CIBALONGSARI 1', 'CIBALONGSARI 2', 'PANCAWATI', 'KALIMULYA'];
        $kelompokLm = ['TAMIANG 1', 'TAMIANG 2', 'CKM', 'TIPAR', 'HNH', 'ANGGADITA', 'KARAWANG MEGAH'];
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

        $role = Role::create([
            'name' => 'phppg',
            'guard_name' => 'web'
        ]);

        Role::insert([
            ['name' => 'mudamudi_daerah', 'guard_name' => 'web'],
            ['name' => 'kurikulum', 'guard_name' => 'web'],
            ['name' => 'tenaga_pendidik', 'guard_name' => 'web'],
            ['name' => 'pjp_desa', 'guard_name' => 'web'],
            ['name' => 'mudamudi_desa', 'guard_name' => 'web'],
            ['name' => 'pjp_kelompok', 'guard_name' => 'web'],
            ['name' => 'mudamudi_kelompok', 'guard_name' => 'web'],
        ]);
        
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@ppg.com',
            'password' => bcrypt('password'),
            'plain_password' => 'password',
        ]);

        $user = User::create([
            'name' => 'PPG',
            'email' => 'ppg@ppg.com',
            'password' => bcrypt('password'),
            'plain_password' => 'password',
            'daerah_id' => 1,
        ]);
        $user->assignRole('phppg');
        $user->assignRole('mudamudi_daerah');

        // Dapukan
        $dapukans = ['GENERUS', 'MUBALIGH TUGASAN', 'MUBALIGH SETEMPAT'];
        foreach($dapukans as $dapukan) {
            Dapukan::create([
                'nm_dapukan' => $dapukan
            ]);
        }

        // Status
        $this->call([
            StatusSeeder::class,
            MinatSeeder::class,
        ]);

        // Individu / Insan
        $insanSatu = [
            'daerah_id' => 1,
            'desa_id' => 1,
            'kelompok_id' => 1,
            'nama' => 'Wisnu Aji Pamungkas',
            'jk' => 'L',
            'kota_lahir' => 'Brebes',
            'tgl_lahir' => '2002-03-05',
            'usia' => 23,
            'no_hp' => '085889634432',
            'pendidikan_terakhir' => 'SMA/K',
            'jurusan' => 'Teknik Komputer & Jaringan'
        ];

        $insan = Insan::create($insanSatu);
        $insanRole = InsanRole::create([
            'insan_id' => $insan->id,
            'dapukan_id' => 1,
        ]);

        Generus::create([
            'insan_role_id' => $insanRole->id,
            'nis' => 123456,
            'jenis_data' => 'MM',
            'kategori' => 'PRA_NIKAH',
            'gol_dar' => 'A',
            'kelas_di_ppg' => 'E',
            'status_id' => 6,
            'detail_status' => 'Sistem Informasi',
            'siap_nikah' => 'BELUM',
            'nm_ayah' => 'Sutarso',
            'nm_ibu' => 'Nuning Handayani',
            'minat_id' => 6,
            'detail_minat' => 'Ngoding & Servis Hardware',
        ]);
    }
}

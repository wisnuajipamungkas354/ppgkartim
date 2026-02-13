<?php

namespace Database\Seeders;

use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat User Super Admin
        User::create([
                'name' => 'Super Admin', 
                'username' => 'superadmin123313', 
                'password' => bcrypt('password'),
                'plain_password' => 'password',
        ])->assignRole('super_admin');

        // // Membuat User PPG sekaligus assign role ph_ppg, mudamudi_daerah, kurikulum dan tenaga_pendidik
        $daerahs = Daerah::all();
        foreach($daerahs as $daerah) {
            $daerah->users()->create([
                'name' => Str::title($daerah->nm_daerah),
                'username' => 'ppgkartim313',
                'password' => bcrypt('password'),
                'plain_password' => 'password'
            ])->assignRole(['ph_ppg', 'mudamudi_daerah', 'kurikulum', 'tenaga_pendidik']);
        }

        $desas = Desa::all();
        foreach($desas as $desa) {
            if($desa->nm_desa !== 'TUNGGAK JATI') $username = Str::of($desa->nm_desa)->replace(' ', '')->lower() . '313';
            else $username = Str::of(($desa->nm_desa . 'desa'))->replace(' ', '')->lower() . '313';

            $desa->users()->create([
                'name' => Str::title($desa->nm_desa),
                'username' => $username,
                'password' => bcrypt('password'),
                'plain_password' => 'password'
            ])->assignRole(['pjp_desa', 'mudamudi_desa']);
        }

        $kelompoks = Kelompok::all();
        foreach($kelompoks as $kelompok) 
        {
            $kelompok->users()->create([
                'name' => Str::title($kelompok->nm_kelompok),
                'username' => Str::of($kelompok->nm_kelompok)->replace(' ', '')->lower() . '313',
                'password' => bcrypt('password'),
                'plain_password' => 'password'
            ])->assignRole(['pjp_kelompok', 'mudamudi_kelompok']);
        }
    }
}

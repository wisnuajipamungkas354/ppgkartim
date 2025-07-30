<?php

namespace Database\Seeders;

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
        User::factory()->create([
                'name' => 'Super Admin', 
                'email' => 'superadmin@ppgkartim.com', 
        ])->assignRole('super_admin');

        // Membuat User PPG sekaligus assign role phppg, mudamudi_daerah, kurikulum dan tenaga_pendidik
        User::factory()->create([
            'name' => 'PPG',
            'email' => 'ppg@ppgkartim.com',  
            'daerah_id' => 1
        ])->assignRole(['phppg', 'mudamudi_daerah', 'kurikulum', 'tenaga_pendidik']);

        // Membuat user tingkat desa sekaligus assign role sebagai pjp_desa & mudamudi_desa
        $desas = Desa::all();
        foreach ($desas as $desa) 
        {
            $email = Str::of($desa->nm_desa)->replace(' ', '')->lower() . '@ppgkartim.com';

            if($email == 'tunggakjati@ppgkartim.com') {
                $email = 'tunggakjatidesa@ppgkartim.com';
            }

            User::factory()->create([
                'name' => Str::title($desa->nm_desa),
                'email' => $email,
                'desa_id' => $desa->id,
            ])->assignRole(['pjp_desa', 'mudamudi_desa']);
        }

        // Membuat user tingkat kelompok sekaligus assign role sebagai pjp_kelompok & mudamudi_kelompok
        $kelompoks = Kelompok::all();
        foreach($kelompoks as $kelompok) 
        {
            User::factory()->create([
                'name' => Str::title($kelompok->nm_kelompok),
                'email' => Str::of($kelompok->nm_kelompok)->replace(' ', '')->lower() . '@ppgkartim.com',
                'kelompok_id' => $kelompok->id,
            ])->assignRole(['pjp_kelompok', 'mudamudi_kelompok']);
        }
    }
}

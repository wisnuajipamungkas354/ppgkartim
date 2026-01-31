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
        // User::factory()->create([
        //         'name' => 'Super Admin', 
        //         'username' => 'superadmin123313', 
        // ])->assignRole('super_admin');

        // // Membuat User PPG sekaligus assign role phppg, mudamudi_daerah, kurikulum dan tenaga_pendidik
        // User::factory()->create([
        //     'name' => 'PPG',
        //     'username' => 'ppgkartim313',  
        //     'daerah_id' => 1
        // ])->assignRole(['phppg', 'mudamudi_daerah', 'kurikulum', 'tenaga_pendidik']);

        // // Membuat user tingkat desa sekaligus assign role sebagai pjp_desa & mudamudi_desa
        // $desas = Desa::all();
        // foreach ($desas as $desa) 
        // {
        //     $email = Str::of($desa->nm_desa)->replace(' ', '')->lower() . '313';

        //     if($email == 'tunggakjati313') {
        //         $email = 'tunggakjatidesa313';
        //     }

        //     User::factory()->create([
        //         'name' => Str::title($desa->nm_desa),
        //         'username' => $email,
        //         'desa_id' => $desa->id,
        //     ])->assignRole(['pjp_desa', 'mudamudi_desa']);
        // }

        // // Membuat user tingkat kelompok sekaligus assign role sebagai pjp_kelompok & mudamudi_kelompok
        // $kelompoks = Kelompok::all();
        // foreach($kelompoks as $kelompok) 
        // {
        //     User::factory()->create([
        //         'name' => Str::title($kelompok->nm_kelompok),
        //         'username' => Str::of($kelompok->nm_kelompok)->replace(' ', '')->lower() . '313',
        //         'kelompok_id' => $kelompok->id,
        //     ])->assignRole(['pjp_kelompok', 'mudamudi_kelompok']);
        // }

        $users = User::all();
        foreach ($users as $user) {
            $email = $user->email;
            $username = str_replace("@ppgkartim.com", '313', $email);
            $user->username = $username;
            $user->save();
        }
    }
}

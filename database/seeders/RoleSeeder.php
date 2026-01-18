<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            ['name' => 'super_admin', 'guard_name' => 'web'],
            ['name' => 'phppg', 'guard_name' => 'web'],
            ['name' => 'mudamudi_daerah', 'guard_name' => 'web'],
            ['name' => 'kurikulum', 'guard_name' => 'web'],
            ['name' => 'tenaga_pendidik', 'guard_name' => 'web'],
            ['name' => 'pjp_desa', 'guard_name' => 'web'],
            ['name' => 'mudamudi_desa', 'guard_name' => 'web'],
            ['name' => 'pjp_kelompok', 'guard_name' => 'web'],
            ['name' => 'mudamudi_kelompok', 'guard_name' => 'web'],
        ]);

        $permissions = [
            'daerahs', 
            'desas', 
            'kelompoks', 
            'dapukans', 
            'event', 
            'generus', 
            'kelas_ppg', 
            'minats', 
            'mubalighs', 
            'roles', 
            'users', 
            'Registrasi', 
            'SwitchRole', 
            'SuperAdminStat', 
            'JumlahOverview', 
            'GenderDoughnut', 
            'KategoriGenerusPie', 
            'ArusGenerusArea', 
            'SensusGenerusBar'
        ];
    }
}

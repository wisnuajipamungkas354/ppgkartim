<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = \App\Models\User::all();

        foreach ($data as $data) {
            if ($data->id >= 7 && $data->id <= 31) {
                $name = strtoupper($data->name);

                \App\Models\User::where('id', $data->id)->update([
                    'userable_id' => (int) $data->id - 6,
                ]);
            }
        }
    }
}

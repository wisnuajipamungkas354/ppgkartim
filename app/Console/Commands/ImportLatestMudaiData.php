<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Insan;
use App\Models\Generus;

class ImportLatestMudaiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-latest-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   
    public function handle()
    {
        $response = Http::withHeaders([
            'X-API-KEY' => env('MIGRATION_KEY_SECRET')
        ])->get(env('MIGRATION_API_URL') . '/api/import-data');

        if (!$response->successful()) {
            $this->error('Gagal ambil data');
            return;
        }

        $mudaiList = $response->json()['data'];

        // foreach ($mudaiList as $mudai) {

        //     // Cek supaya tidak duplicate
        //     $exists = Insan::where('nik', $mudai['nik'])->first();
        //     if ($exists) {
        //         $this->info("Skip: {$mudai['nama']}");
        //         continue;
        //     }

        //     // Insert ke tabel insans dulu
        //     $insan = Insan::create([
        //         'nama' => $mudai['nama'],
        //         'nik' => $mudai['nik'],
        //         'tanggal_lahir' => $mudai['tanggal_lahir'],
        //         'jenis_kelamin' => $mudai['jenis_kelamin'],
        //     ]);

        //     // Insert ke generuses
        //     Generus::create([
        //         'insan_id' => $insan->id,
        //         'status' => 'aktif',
        //     ]);

        //     $this->info("Import: {$mudai['nama']}");
        // }

        $this->info('Selesai migrasi.');
    }
}

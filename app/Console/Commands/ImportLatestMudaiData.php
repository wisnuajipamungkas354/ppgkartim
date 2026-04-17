<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Insan;
use App\Models\Generus;
use Illuminate\Support\Str;

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
            'X-API-KEY' => 'Wisnu123',
            'User-Agent' => 'Laravel-Migration-Script'
        ])->get('https://ppgkartim.com/api/import-data');

        if (!$response->successful()) {
            $this->error('Gagal ambil data');
            return;
        }

        $mudaiList = $response->json()['data'];

        foreach ($mudaiList as $mudai) {

            $nama = Str::upper($mudai['nama']);
            $tglLahir = $mudai['tgl_lahir'];

            // Cek supaya tidak duplicate
            $exists = Insan::where('nama',  $nama)->where('tgl_lahir', $tglLahir)->first();
            if ($exists) {
                $this->info("Skip: {$mudai['nama']}");
                continue;
            }

            // Insert ke tabel insans dulu
            $insan = Insan::create([
                'nama' => $mudai['nama'],
                'jk' => $mudai['jk'],
                'tanggal_lahir' => $mudai['tanggal_lahir'],
                'jenis_kelamin' => $mudai['jenis_kelamin'],
            ]);

            // Insert ke generuses
            Generus::create([
                'insan_id' => $insan->id,
                'status' => 'aktif',
            ]);

            $this->info("Import: {$mudai['nama']}");
        }

        $this->info('Selesai migrasi.');
    }
}

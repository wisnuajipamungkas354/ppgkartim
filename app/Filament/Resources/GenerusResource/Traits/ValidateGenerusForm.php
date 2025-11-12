<?php

namespace App\Filament\Resources\GenerusResource\Traits;

use App\Helpers\AccessHelper;
use App\Models\Dapukan;
use App\Models\Desa;
use App\Models\Generus;
use App\Models\Insan;
use App\Models\Kelompok;
use App\Models\Mubaligh;
use App\Models\Status;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

trait ValidateGenerusForm {
    // Memvalidasi Input Form dan membuat struktur data (agar siap untuk disimpan)
    public function validateInput(array $data) {

        // Step 1: Melengkapi Tempat Sambung
        if(!isset($data['daerah_id'])) {
            // Jika yang sedang login adalah kelompok maka fill otomatis daerah, desa dan kelompoknya
            if (AccessHelper::isKelompok()) {
                $kelompok               = Kelompok::with('desa.daerah')->findOrFail(auth()->user()->kelompok_id);
                $data['daerah_id']      = $kelompok->desa->daerah->id ?? null;
                $data['desa_id']        = $kelompok->desa->id ?? null;
                $data['kelompok_id']    = $kelompok->id;

            // Jika yang sedang login adalah desa maka fill otomatis daerah dan desanya
            } elseif (AccessHelper::isDesa()) {
                $data['desa_id']        = auth()->user()->desa_id;
                $data['daerah_id']      = Desa::where('id', $data['desa_id'])->value('daerah_id');

            // Jika yang sedang login adalah daerah, maka fill otomatis daerahnya saja
            } elseif (AccessHelper::isDaerah()) {
                $data['daerah_id']      = auth()->user()->daerah_id;
            }
        }

        // Step 2: Jenis Data
        $data['jenis_data'] = in_array($data['kategori'], ['PAUD', 'CABERAWIT']) ? 'CBRWT' : 'MM';

        // Step 3: Status
        $statusMap = [
            'PAUD' => 'paudtk',
            'CABERAWIT' => 'sd',
            'PRA_REMAJA' => 'smp',
            'REMAJA' => 'sma-smk',
        ];

        $slug = $statusMap[$data['kategori']] ?? null;
        $data['status_id'] = $slug ? Status::where('slug', $slug)->value('id') : ($data['mubaligh'] == 'MT' ? Status::where('slug', 'mt')->value('id') : $data['status_id']);
        // Step 4: Usia
        $data['usia'] = Carbon::parse($data['tgl_lahir'])->age ?? null;

        // Step 5: Data Terverifikasi
        $data['is_verified'] = true;
        $data['riwayat_update'] = 'DITAMBAHKAN OLEH ADMIN';

        // Step 6: Pisahkan dan kumpulkan data detail status dan detail siap nikah
        $detailStatusKeys = [
            'program_studi', 'universitas', 'jabatan', 'nm_perusahaan',
            'bidang_usaha', 'nm_usaha', 'keahlian', 'nm_sekolah',
            'peminatan_sekolah', 'kelas_di_sekolah', 'is_sekolah_jm'
        ];
        $detailSiapNikah = ['tinggi_badan', 'berat_badan', 'kriteria_pasangan'];

        foreach ($detailStatusKeys as $key) {
            if (isset($data[$key])) {
                $data['detail_status'][$key] = $data[$key];
                unset($data[$key]);
            }
        }

        foreach ($detailSiapNikah as $key) {
            if(isset($data[$key])) {
                $data['detail_siap_nikah'][$key] = $data[$key];
                unset($data[$key]);
            }
        }
        
        // Step 7: Menentukan Dapukan 
        $dapukanGenerus = Dapukan::where('nm_dapukan', 'GENERUS')->value('slug');
        $dapukanMubaligh = null; 
        if (isset($data['mubaligh']) && $data['mubaligh'] !== 'BUKAN') {
            if ($data['mubaligh'] === 'MT') {
                $dapukanMubaligh = Dapukan::where('nm_dapukan', 'MUBALIGH TUGASAN')->value('slug');
            } elseif ($data['mubaligh'] === 'MS') {
                $dapukanMubaligh = Dapukan::where('nm_dapukan', 'MUBALIGH SETEMPAT')->value('slug');
            }
        }
        
        return [$data, $dapukanGenerus, $dapukanMubaligh];
  }
}
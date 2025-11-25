<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use App\Filament\Resources\GenerusResource\Pages\Forms\BaseFormGenerus;
use App\Models\Generus;
use App\Models\Insan;
use App\Models\Mubaligh;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGenerus extends EditRecord
{
    use EditRecord\Concerns\HasWizard;

    protected static string $resource = GenerusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Hapus'),
        ];
    }

    protected function getRedirectUrl(): ?string
    {
        return route('filament.admin.resources.generuses.index');
    }

    public function getSteps(): array
    {
        return BaseFormGenerus::getBaseForm();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = Generus::query()->with('insan')->where('id', $data['id'])->first();

        $dataMS = [];
        if($record->insan->is_mubaligh) {
            $dataMS = Mubaligh::where('insan_id', $record->insan->id)->first();
        }

        // Menggabungkan semua data yang diperlukan
        return [
            'daerah_id' => $record->insan->daerah_id,
            'desa_id' => $record->insan->desa_id,
            'kelompok_id' => $record->insan->kelompok_id,
            'nama' => $record->insan->nama,
            'jk' => $record->insan->jk,
            'kota_lahir' => $record->insan->kota_lahir,
            'tgl_lahir' => $record->insan->tgl_lahir,
            'gol_dar' => $record->insan->gol_dar,
            'no_hp' => $record->insan->no_hp,
            'pendidikan_terakhir' => $record->insan->pendidikan_terakhir,
            'jurusan' => $record->insan->jurusan,
            'nm_ayah' => $record->insan->nm_ayah,
            'nm_ibu' => $record->insan->nm_ibu,
            'no_hp_wali' => $record->insan->no_hp_wali,
            'minat_bakat' => $record->insan->minat_bakat,
            'siap_nikah' => $record->insan->siap_nikah,
            
            'nis' => $record->nis,
            'jenis_data' => $record->jenis_data,
            'kategori' => $record->kategori,
            'kelas_ppg_id' => $record->kelas_ppg_id,
            'mubaligh' => $record->insan->is_mubaligh ? 'MS' : 'BUKAN', 
            'asal_pondok' => $dataMS->asal_pondok ?? null, 
            'jml_tugas' => $dataMS->jml_tugas ?? null, 
            'lama_tugas' => $dataMS->lama_tugas ?? null, 
            'konfirmasi_kesiapan_tugas' => $dataMS->konfirmasi_kesiapan_tugas ?? null,
            'status_id' => $record->status_id,
            'program_studi' => $record->detail_status['program_studi'] ?? null, 
            'universitas' => $record->detail_status['universitas'] ?? null, 
            'jabatan' => $record->detail_status['jabatan'] ?? null, 
            'nm_perusahaan' => $record->detail_status['nm_perusahaan'] ?? null, 
            'bidang_usaha' => $record->detail_status['bidang_usaha'] ?? null, 
            'nm_usaha' => $record->detail_status['nm_usaha'] ?? null, 
            'keahlian' => $record->detail_status['keahlian'] ?? null,
            'nm_sekolah' => $record->detail_status['nm_sekolah'] ?? null,
            'kelas_di_sekolah' => $record->detail_status['kelas_di_sekolah'] ?? null,
            'peminatan_sekolah' => $record->detail_status['peminatan_sekolah'] ?? null,
            'is_sekolah_jm' => $record->detail_status['is_sekolah_jm'] ?? null,
            'is_verified' => $record->is_verified,
            'riwayat_update' => $record->riwayat_update,
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Step 1: Update data Insan
        $insan = Insan::find($record->insan->id);
        if($data['mubaligh'] == 'MS') {
            Mubaligh::updateOrCreate([
                        'insan_id' => $record->insan->id,
                        'asal_pondok' => $data['asal_pondok'] ?? null,
                        'jml_tugas' => $data['jml_tugas'] ?? null,
                        'lama_tugas' => $data['lama_tugas'] ?? null,
                        'konfirmasi_kesiapan_tugas' => $data['konfirmasi_kesiapan_tugas'] ?? null
                    ]);
        }
        $insan->update($data);
        $record->update($data);

        return $record;
    }
}
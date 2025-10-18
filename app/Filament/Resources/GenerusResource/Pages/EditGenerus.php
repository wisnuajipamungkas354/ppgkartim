<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use App\Filament\Resources\GenerusResource\Pages\Forms\BaseFormGenerus;
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
        $insan = Insan::find($data['insan_id'])?->insan;
        if (!$insan) {
            return $data; // atau bisa throw exception kalau perlu
        }

        $insanData = $insan->only([
            'url_foto', 'daerah_id', 'desa_id', 'kelompok_id', 'nama', 'jk',
            'kota_lahir', 'tgl_lahir', 'no_hp', 'pendidikan_terakhir', 'jurusan',
        ]);

        $mubalighTugasan = [];
        $mubalighSetempat = [];
        foreach($insan->insanRole as $role => $dapukan){
            if($insan->insanRole[$role]->dapukan->nm_dapukan == 'MUBALIGH TUGASAN') {
                $mubalighTugasan = Mubaligh::where('insan_role_id', $insan->insanRole[$role]->id)->first();
            } elseif($insan->insanRole[$role]->dapukan->nm_dapukan == 'MUBALIGH SETEMPAT') {
                $mubalighSetempat = Mubaligh::where('insan_role_id', $insan->insanRole[$role]->id)->first();
            }
        }
        $mubalighData = [];

        if ($mubalighTugasan) {
            $mubalighData = [
                'mubaligh' => 'MT',
                'tingkatan_tugas' => $mubalighTugasan->tingkatan_tugas,
                'asal_pondok' => $mubalighTugasan->asal_pondok,
                'tugasan_ke' => $mubalighTugasan->tugasan_ke,
                'tgl_mulai_tugas' => $mubalighTugasan->tgl_mulai_tugas,
            ];
        } elseif ($mubalighSetempat) {
            $mubalighData = [
                'mubaligh' => 'MS',
                'asal_pondok' => $mubalighSetempat->asal_pondok,
                'jml_tugas' => $mubalighSetempat->jml_tugas,
                'lama_tugas' => $mubalighSetempat->lama_tugas,
            ];
        } else {
            $mubalighData['mubaligh'] = 'BUKAN';
        }

        // Mengeluarkan nilai detail status
        $detailStatus = $data['detail_status'];

        // Menggabungkan semua data yang diperlukan
        return array_merge($data, $insanData, $mubalighData, $detailStatus, $insan->only([
            'url_foto', 'daerah_id', 'desa_id', 'kelompok_id', 'nama', 'jk',
            'kota_lahir', 'tgl_lahir', 'no_hp', 'pendidikan_terakhir', 'jurusan',
        ]));
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Step 1: Update data Insan
        $insan = $record->insanRole->insan;
        $insanData = [
            'nama' => $data['nama'],
            'jk' => $data['jk'],
            'kota_lahir' => $data['kota_lahir'],
            'tgl_lahir' => $data['tgl_lahir'],
            'no_hp' => $data['no_hp'] ?? null,
            'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? null,
            'jurusan' => $data['jurusan'] ?? null,
            'usia' => Carbon::parse($data['tgl_lahir'])->age ?? null,
        ];
        $insan->update($insanData);

        // Step 2: Pisahkan dan kumpulkan data detail status
        $detailStatusKeys = [
            'program_studi', 'universitas', 'jabatan', 'nm_perusahaan',
            'bidang_usaha', 'nm_usaha', 'keahlian', 'nm_sekolah',
            'peminatan_sekolah', 'kelas_di_sekolah',
        ];

        $detailStatusData = [];
        foreach ($detailStatusKeys as $key) {
            if (isset($data[$key])) {
                $detailStatusData[$key] = $data[$key];
                unset($data[$key]);
            }
        }

        // Step 3: Update data Generus
        $generusData = [
            'jenis_data' => in_array($data['kategori'], ['PAUD', 'CABERAWIT']) ? 'CBRWT' : 'MM',
            'kategori' => $data['kategori'],
            'gol_dar' => $data['gol_dar'] ?? null,
            'kelas_ppg_id' => $data['kelas_ppg_id'] ?? null,
            'status_id' => $data['status_id'] ?? null,
            'detail_status' => $detailStatusData,
            'nm_ayah' => $data['nm_ayah'] ?? null,
            'nm_ibu' => $data['nm_ibu'] ?? null,
            'no_hp_wali' => $data['no_hp_wali'] ?? null,
            'minat_id' => $data['minat_id'] ?? null,
            'detail_minat' => $data['detail_minat'] ?? null,
            'siap_nikah' => $data['siap_nikah'] ?? null,
            'riwayat_update' => 'DIEDIT OLEH ADMIN',
        ];
        $record->update($generusData);

        // Step 4: Update data Mubaligh jika ada
        if (isset($data['mubaligh']) && $data['mubaligh'] != 'BUKAN') {
            $insanRoleId = $record->insan_role_id;

            // Hapus data Mubaligh yang tidak relevan (jika jenis Mubaligh berubah)
            if ($data['mubaligh'] === 'MT') {
                Mubaligh::where('insan_role_id', $insanRoleId)->delete();
                Mubaligh::updateOrCreate(
                    ['insan_role_id' => $insanRoleId],
                    [
                        'tingkatan_tugas' => $data['tingkatan_tugas'] ?? null,
                        'asal_pondok' => $data['asal_pondok'] ?? null,
                        'tugasan_ke' => $data['tugasan_ke'] ?? null,
                        'tgl_mulai_tugas' => $data['tgl_mulai_tugas'] ?? null,
                    ]
                );
            } elseif ($data['mubaligh'] === 'MS') {
                Mubaligh::where('insan_role_id', $insanRoleId)->delete();
                Mubaligh::updateOrCreate(
                    ['insan_role_id' => $insanRoleId],
                    [
                        'asal_pondok' => $data['asal_pondok'] ?? null,
                        'jml_tugas' => $data['jml_tugas'] ?? null,
                        'lama_tugas' => $data['lama_tugas'] ?? null,
                    ]
                );
            } else { // Jika bukan Mubaligh
                Mubaligh::where('insan_role_id', $insanRoleId)->delete();
                Mubaligh::where('insan_role_id', $insanRoleId)->delete();
            }
        }

        return $record;
    }
}
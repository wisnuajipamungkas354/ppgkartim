<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use App\Filament\Resources\GenerusResource\Pages\Forms\BaseFormGenerus;
use App\Helpers\AccessHelper;
use App\Models\Desa;
use App\Models\Dapukan;
use App\Models\Insan;
use App\Models\InsanRole;
use App\Models\Kelompok;
use App\Models\Status;
use App\Models\MubalighTugasan;
use App\Models\MubalighSetempat;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Js;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreateGenerus extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = GenerusResource::class;

    public function getTitle(): string|Htmlable
    {
        return 'Tambah Data Generus';
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label('Simpan')
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return Action::make('createAnother')
            ->label('Simpan & Tambah Data Lagi')
            ->action('createAnother')
            ->keyBindings(['mod+shift+s'])
            ->color('gray');
    }

    protected function getCancelFormAction(): Action
    {
        return Action::make('cancel')
            ->label('Batal')
            ->alpineClickHandler('document.referrer ? window.history.back() : (window.location.href = ' . Js::from($this->previousUrl ?? static::getResource()::getUrl()) . ')')
            ->color('gray');
    }

    public function getSteps(): array
    {
        return BaseFormGenerus::getBaseForm();
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            // Step 1: Lengkapi Daerah & Desa
            if (AccessHelper::isKelompok()) {
                $kelompok = Kelompok::with('desa.daerah')->findOrFail(auth()->user()->kelompok_id);
                $data['daerah_id'] = $kelompok->desa->daerah->id ?? null;
                $data['desa_id'] = $kelompok->desa->id ?? null;
                $data['kelompok_id'] = $kelompok->id;
            } elseif (AccessHelper::isDesa()) {
                $data['desa_id'] = auth()->user()->desa_id;
                $data['daerah_id'] = Desa::where('id', $data['desa_id'])->value('daerah_id');
            } elseif (AccessHelper::isDaerah()) {
                $data['daerah_id'] = auth()->user()->daerah_id;
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
            $data['status_id'] = $slug ? Status::where('slug', $slug)->value('id') : $data['status_id'];

            // Step 4: Usia
            $data['usia'] = Carbon::parse($data['tgl_lahir'])->age ?? null;

            // Step 5: Data Terverifikasi
            $data['is_verified'] = true;
            $data['riwayat_update'] = 'DITAMBAHKAN OLEH ADMIN';

            // Step 6: Pisahkan dan kumpulkan data detail status
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
            
            // Step 7: Menentukan Dapukan 
            $dapukanGenerus = Dapukan::where('nm_dapukan', 'GENERUS')->value('id');
            $dapukanMubaligh = null; 
            if (isset($data['mubaligh']) && $data['mubaligh'] !== 'BUKAN') {
                if ($data['mubaligh'] === 'MT') {
                    $dapukanMubaligh = Dapukan::where('nm_dapukan', 'MUBALIGH TUGASAN')->value('id');
                } elseif ($data['mubaligh'] === 'MS') {
                    $dapukanMubaligh = Dapukan::where('nm_dapukan', 'MUBALIGH SETEMPAT')->value('id');
                }
            }

            // Step 8: Simpan Insan
            $insan = Insan::create([
                'url_foto' => $data['url_foto'] ?? null,
                'daerah_id' => $data['daerah_id'],
                'desa_id' => $data['desa_id'],
                'kelompok_id' => $data['kelompok_id'] ?? null,
                'nama' => $data['nama'],
                'jk' => $data['jk'],
                'kota_lahir' => $data['kota_lahir'],
                'tgl_lahir' => $data['tgl_lahir'],
                'usia' => $data['usia'],
                'no_hp' => $data['no_hp'] ?? null,
                'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? null,
                'jurusan' => $data['jurusan'] ?? null,
            ]);

            // Step 9: Simpan Insan Role
            $insanRoleGenerus = InsanRole::create([
                'insan_id' => $insan->id,
                'dapukan_id' => $dapukanGenerus,
            ]);
            $insanRoleMubaligh = '';
            
            // Step 10: Simpan Data Mubaligh jika ada
            if (isset($data['mubaligh']) && $data['mubaligh'] !== 'BUKAN') {
                $insanRoleMubaligh = InsanRole::create([
                    'insan_id' => $insan->id,
                    'dapukan_id' => $dapukanMubaligh,
                ]);

                if ($data['mubaligh'] === 'MT') {
        
                    MubalighTugasan::create([
                        'insan_role_id' => $insanRoleMubaligh->id,
                        'tingkatan_tugas' => $data['tingkatan_tugas'] ?? null,
                        'asal_pondok' => $data['asal_pondok'] ?? null,
                        'tugasan_ke' => $data['tugasan_ke'] ?? null,
                        'tgl_mulai_tugas' => $data['tgl_mulai_tugas'] ?? null,
                    ]);
                } elseif ($data['mubaligh'] === 'MS') {
                    MubalighSetempat::create([
                        'insan_role_id' => $insanRoleMubaligh->id,
                        'asal_pondok' => $data['asal_pondok'] ?? null,
                        'jml_tugas' => $data['jml_tugas'] ?? null,
                        'lama_tugas' => $data['lama_tugas'] ?? null,
                    ]);
                }
                unset($data['mubaligh']);
            }

            // Step 11: Simpan Generus Record
            return static::getModel()::create([
                'insan_role_id' => $insanRoleGenerus->id,
                'nis' => $data['nis'] ?? null,
                'jenis_data' => $data['jenis_data'],
                'kategori' => $data['kategori'],
                'gol_dar' => $data['gol_dar'] ?? null,
                'kelas_ppg_id' => $data['kelas_ppg_id'] ?? null,
                'status_id' => $data['status_id'] ?? null,
                'detail_status' => $detailStatusData,
                'nm_ayah' => $data['nm_ayah'] ?? null,
                'nm_ibu' => $data['nm_ibu'] ?? null,
                'no_hp_wali' => $data['no_hp_wali'] ?? null,
                'minat_id' => $data['minat_id'][0] ?? null,
                'detail_minat' => $data['detail_minat'] ?? null,
                'siap_nikah' => $data['siap_nikah'] ?? null,
                'is_verified' => $data['is_verified'],
                'riwayat_update' => $data['riwayat_update'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal simpan data generus: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            Notification::make('failed')
                ->title('Gagal')
                ->danger()
                ->body('Terjadi kesalahan dalam menyimpan data, harap laporkan ini ke Super Admin')
                ->send();

            throw new \Exception("Gagal menyimpan record", 500);
        }
    }
}
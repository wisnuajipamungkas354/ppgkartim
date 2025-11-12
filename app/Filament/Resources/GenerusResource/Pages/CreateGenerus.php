<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use App\Filament\Resources\GenerusResource\Pages\Forms\BaseFormGenerus;
use App\Helpers\AccessHelper;
use App\Models\Desa;
use App\Models\Dapukan;
use App\Models\Insan;
use App\Models\Kelompok;
use App\Models\Status;
use App\Models\Mubaligh;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Js;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Filament\Resources\GenerusResource\Traits\ValidateGenerusForm;
use SebastianBergmann\Type\FalseType;

class CreateGenerus extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard, ValidateGenerusForm;

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
            [$result, $dapukanGenerus, $dapukanMubaligh] = $this->validateInput($data);

            // Step 8: Simpan Insan
            $insan = Insan::create([
                'url_foto' => $result['url_foto'] ?? null,
                'daerah_id' => $result['daerah_id'],
                'desa_id' => $result['desa_id'],
                'kelompok_id' => $result['kelompok_id'] ?? null,
                'nama' => $result['nama'],
                'jk' => $result['jk'],
                'kota_lahir' => $result['kota_lahir'],
                'tgl_lahir' => $result['tgl_lahir'],
                'gol_dar' => $result['gol_dar'] ?? null,
                'usia' => $result['usia'],
                'no_hp' => $result['no_hp'] ?? null,
                'pendidikan_terakhir' => $result['pendidikan_terakhir'] ?? null,
                'jurusan' => $result['jurusan'] ?? null,
                'dapukan' => [
                    $dapukanGenerus
                ],
                'perkawinan' => 'LAJANG',
                'nm_ayah' => $result['nm_ayah'] ?? null,
                'nm_ibu' => $result['nm_ibu'] ?? null,
                'no_hp_wali' => $result['no_hp_wali'] ?? null,
                'minat_id' => $result['minat_id'][0] ?? null,
                'detail_minat' => $result['detail_minat'] ?? null,
                'siap_nikah' => $result['siap_nikah'] ?? null,
                'detail_siap_nikah' => $result['detail_siap_nikah'] ?? null,
            ]);
            
            // Step 10: Simpan Data Mubaligh jika ada
            if (isset($result['mubaligh']) && $result['mubaligh'] !== 'BUKAN') {
                if ($result['mubaligh'] === 'MT') {
                    Mubaligh::create([
                        'insan_id' => $insan->id,
                        'kategori' => $result['mubaligh'],
                        'tingkatan_tugas' => $result['tingkatan_tugas'] ?? null,
                        'asal_pondok' => $result['asal_pondok'] ?? null,
                        'tugasan_ke' => $result['tugasan_ke'] ?? null,
                        'tgl_mulai_tugas' => $result['tgl_mulai_tugas'] ?? null,
                    ]);
                } elseif ($result['mubaligh'] === 'MS') {
                    Mubaligh::create([
                        'insan_id' => $insan->id,
                        'asal_pondok' => $result['asal_pondok'] ?? null,
                        'jml_tugas' => $result['jml_tugas'] ?? null,
                        'lama_tugas' => $result['lama_tugas'] ?? null,
                    ]);
                }
                unset($result['mubaligh']);
            }

            // Step 11: Simpan Generus Record
            return static::getModel()::create([
                'insan_id' => $insan->id,
                'nis' => $result['nis'] ?? null,
                'jenis_data' => $result['jenis_data'],
                'kategori' => $result['kategori'],
                'kelas_ppg_id' => $result['kelas_ppg_id'] ?? null,
                'status_id' => $result['status_id'] ?? null,
                'detail_status' => $result['detail_status'] ?? null,
                'aktif_mengajar' => $result['aktif_mengajar'] ?? false,
                'is_verified' => $result['is_verified'],
                'riwayat_update' => $result['riwayat_update'],
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
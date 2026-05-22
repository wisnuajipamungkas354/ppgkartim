<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use App\Filament\Resources\GenerusResource\Pages\Forms\BaseFormGenerus;
use App\Helpers\AccessHelper;
use App\Models\Desa;
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
use App\Models\Minat;
use Illuminate\Support\Str;
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

    public function hasSkippableSteps(): bool
    {
        return true;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $result = $this->validateInput($data, 'ADMIN');
        
        if(isset($result['bakat_lainnya']) && $result['bakat_lainnya'] !== null ) {
            $namaMinat = Str::camel($result['bakat_lainnya']);
            $slug = Str::slug($namaMinat);

            Minat::create([
                'nm_minat' => $namaMinat,
                'slug' => $slug
            ]);

            $data['minat_bakat'][] = $slug;
        }

        // Step 8: Simpan Insan
        $insan = Insan::create([
            'url_foto'              => $result['url_foto'] ?? null,
            'daerah_id'             => $result['daerah_id'],
            'desa_id'               => $result['desa_id'],
            'kelompok_id'           => $result['kelompok_id'] ?? null,
            'nama'                  => $result['nama'],
            'jk'                    => $result['jk'],
            'kota_lahir'            => $result['kota_lahir'],
            'tgl_lahir'             => $result['tgl_lahir'],
            'gol_dar'               => $result['gol_dar'] ?? null,
            'usia'                  => $result['usia'],
            'no_hp'                 => $result['no_hp'] ?? null,
            'pendidikan_terakhir'   => $result['pendidikan_terakhir'] ?? null,
            'jurusan'               => $result['jurusan'] ?? null,
            'perkawinan'            => 'LAJANG',
            'nm_ayah'               => $result['nm_ayah'] ?? null,
            'nm_ibu'                => $result['nm_ibu'] ?? null,
            'no_hp_wali'            => $result['no_hp_wali'] ?? null,
            'minat_bakat'           => $result['minat_bakat'] ?? null,
            'siap_nikah'            => $result['siap_nikah'] ?? null,
            'detail_siap_nikah'     => $result['detail_siap_nikah'] ?? null,
            'is_mubaligh'           => $result['is_mubaligh'],
        ]);
        
        // Step 10: Simpan Data Mubaligh jika ada
        if (isset($result['mubaligh']) && $result['mubaligh'] !== 'BUKAN') {
            if ($result['mubaligh'] === 'MS') {
                Mubaligh::create([
                    'insan_id'      => $insan->id,
                    'kategori'      => 'MS',
                    'asal_pondok'   => $result['asal_pondok'] ?? null,
                    'jml_tugas'     => $result['jml_tugas'] ?? null,
                    'lama_tugas'    => $result['lama_tugas'] ?? null,
                    'konfirmasi_kesiapan_tugas' => $result['konfirmasi_kesiapan_tugas'] ?? null,
                ]);
            }
            unset($result['mubaligh']);
        }

        // Step 11: Simpan Generus Record
        return static::getModel()::create([
            'insan_id'          => $insan->id,
            'nis'               => $result['nis'] ?? null,
            'jenis_data'        => $result['jenis_data'],
            'kategori'          => $result['kategori'],
            'kelas_ppg_id'      => $result['kelas_ppg_id'] ?? null,
            'status_id'         => $result['status_id'] ?? null,
            'detail_status'     => $result['detail_status'] ?? null,
            'aktif_mengajar'    => $result['aktif_mengajar'] ?? false,
            'is_verified'       => $result['is_verified'],
            'riwayat_update'    => $result['riwayat_update'],
        ]);
    }
}
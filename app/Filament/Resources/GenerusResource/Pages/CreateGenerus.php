<?php

namespace App\Filament\Resources\GenerusResource\Pages;

use App\Filament\Resources\GenerusResource;
use App\Helpers\AccessHelper;
use App\Models\Daerah;
use App\Models\Desa;
use App\Models\Dapukan;
use App\Models\Insan;
use App\Models\InsanRole;
use App\Models\Kelompok;
use App\Models\Status;
use Carbon\Carbon;
use Error;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Js;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreateGenerus extends CreateRecord
{
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

            // Step 5: Simpan Insan
            $insan = Insan::create([
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

            // Step 6: Simpan Insan Role
            $dapukanId = Dapukan::where('nm_dapukan', 'GENERUS')->value('id');
            $insanRole = InsanRole::create([
                'insan_id' => $insan->id,
                'dapukan_id' => $dapukanId,
            ]);

            // Step 7: Simpan Generus Record
            return static::getModel()::create([
                'insan_role_id' => $insanRole->id,
                'nis' => '123345', // TODO: Ganti dengan generator NIS jika perlu
                'jenis_data' => $data['jenis_data'],
                'kategori' => $data['kategori'],
                'gol_dar' => $data['gol_dar'] ?? null,
                'kelas_di_ppg' => $data['kelas_di_ppg'] ?? null,
                'status_id' => $data['status_id'],
                'detail_status' => $data['detail_status'],
                'kelas_di_sekolah' => $data['kelas_di_sekolah'] ?? null,
                'nm_ayah' => $data['nm_ayah'] ?? null,
                'nm_ibu' => $data['nm_ibu'] ?? null,
                'no_hp_wali' => $data['no_hp_wali'] ?? null,
                'minat_id' => $data['minat_id'][0] ?? null,
                'detail_minat' => $data['detail_minat'] ?? null,
                'siap_nikah' => $data['siap_nikah'] ?? null,
            ]);
        } catch (\Throwable $e) {
            // Log error jika perlu
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

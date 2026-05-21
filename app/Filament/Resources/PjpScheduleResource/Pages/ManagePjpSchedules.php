<?php

namespace App\Filament\Resources\PjpScheduleResource\Pages;

use App\Filament\Resources\PjpScheduleResource;
use App\Models\Desa;
use App\Models\PjpSchedule;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Contracts\Support\Htmlable;

class ManagePjpSchedules extends ManageRecords
{
    protected static string $resource = PjpScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Jadwal')
                ->icon('heroicon-o-plus')
                ->createAnother(false)
                ->modalHeading('Buat Jadwal Laporan PJP')
                ->action(function (array $data, PjpSchedule $pjpSchedule) {
                    $user = auth('web')->user();
                    
                    $scheduleDaerah = [
                        'scheduleable_type' => $user->userable_type,
                        'scheduleable_id' => $user->userable_id,
                        'bulan' => $data['bulan'],
                        'tahun' => $data['tahun'],
                        'deadline_laporan' => $data['deadline_desa'],
                        'musyawaroh_rutin' => $data['musyawaroh_desa'],
                        'kegiatan_rutin' => $data['kegiatan_desa'],
                        'status' => 'DIBUKA'
                    ];
                    PjpSchedule::create($scheduleDaerah);

                    $listDesa = Desa::where('daerah_id', $user->userable_id)->get();
                    foreach ($listDesa as $desa) {
                        $scheduleDesa = [
                            'scheduleable_type' => \App\Models\Desa::class,
                            'scheduleable_id' => $desa->id,
                            'bulan' => $data['bulan'],
                            'tahun' => $data['tahun'],
                            'deadline_laporan' => $data['deadline_kelompok'],
                            'musyawaroh_rutin' => $data['musyawaroh_kelompok'],
                            'kegiatan_rutin' => $data['kegiatan_kelompok'],
                            'status' => 'DRAFT'
                        ];

                        PjpSchedule::create($scheduleDesa);
                    }
                })
                ->modalSubmitActionLabel('Buat Jadwal')
                ->modalCancelActionLabel('Batal')
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return 'Monitoring Bulanan';
    }
}

<?php

namespace App\Observers;

use App\Models\PjpKegiatanReport;
use App\Models\PjpMusyawarohReport;
use App\Models\PjpReport;
use App\Models\PjpSchedule;

class PjpScheduleObserver
{
    /**
     * Handle the PjpSchedule "created" event.
     */
    public function created(PjpSchedule $pjpSchedule): void
    {
        if ($pjpSchedule->scheduleable_type === \App\Models\Daerah::class) {

            $desaList = $pjpSchedule->scheduleable->desa;
            // pastikan ada relasi desas() di model Daerah

            foreach ($desaList as $desa) {
                PjpReport::create([
                    'pjp_schedule_id' => $pjpSchedule->id,
                    'reportable_type' => \App\Models\Desa::class,
                    'reportable_id' => $desa->id,
                    'status' => 'BELUM DIISI',
                ]);
            }
        }
    }

    /**
     * Handle the PjpSchedule "updated" event.
     */
    public function updated(PjpSchedule $pjpSchedule): void
    {
        // Kalau schedule dibuat oleh Desa (opsional)
        if ($pjpSchedule->scheduleable_type === \App\Models\Desa::class) {

            $kelompokList = $pjpSchedule->scheduleable->kelompok;

            foreach ($kelompokList as $kelompok) {
                PjpReport::create([
                    'pjp_schedule_id' => $pjpSchedule->id,
                    'reportable_type' => \App\Models\Kelompok::class,
                    'reportable_id' => $kelompok->id,
                    'status' => 'BELUM DIISI',
                ]);
            }
        }
    }

    /**
     * Handle the PjpSchedule "deleted" event.
     */
    public function deleted(PjpSchedule $pjpSchedule): void
    {
        //
    }

    /**
     * Handle the PjpSchedule "restored" event.
     */
    public function restored(PjpSchedule $pjpSchedule): void
    {
        //
    }

    /**
     * Handle the PjpSchedule "force deleted" event.
     */
    public function forceDeleted(PjpSchedule $pjpSchedule): void
    {
        //
    }
}

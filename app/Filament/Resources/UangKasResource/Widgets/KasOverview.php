<?php

namespace App\Filament\Resources\UangKasResource\Widgets;

use App\Filament\Resources\UangKasResource\Pages\ListUangKas;
use App\Models\UangKas;
use App\Traits\MoneyFormat;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class KasOverview extends BaseWidget
{
    use InteractsWithPageTable, MoneyFormat;

    protected function getTablePage(): string
    {
        return ListUangKas::class;
    }

    protected function getStats(): array
    {
        $filters = $this->tableFilters['filter'] ?? [];

        $from = $filters['dari'] ?? now()->startOfMonth()->toDateString();
        $to   = $filters['sampai'] ?? now()->endOfMonth()->toDateString();

        Carbon::setLocale('id');
        $namaBulan = Carbon::parse($from)->translatedFormat('F');
        // =========================
        // SALDO AWAL
        // =========================
        $saldoAwal = UangKas::whereDate('tgl_transaksi', '<', $from)
            ->selectRaw("
                COALESCE(SUM(CASE WHEN jenis_kas='PEMASUKAN' THEN nominal ELSE 0 END),0) -
                COALESCE(SUM(CASE WHEN jenis_kas='PENGELUARAN' THEN nominal ELSE 0 END),0)
                AS saldo
            ")->value('saldo');

        // =========================
        // PEMASUKAN PERIODE
        // =========================
        $pemasukan = UangKas::whereBetween('tgl_transaksi', [$from, $to])
            ->where('jenis_kas', 'PEMASUKAN')
            ->sum('nominal');

        // =========================
        // PENGELUARAN PERIODE
        // =========================
        $pengeluaran = UangKas::whereBetween('tgl_transaksi', [$from, $to])
            ->where('jenis_kas', 'PENGELUARAN')
            ->sum('nominal');

        // =========================
        // SALDO AKHIR
        // =========================
        $saldoAkhir = $saldoAwal + ($pemasukan - $pengeluaran);

        return [
            Stat::make('Saldo Awal Bulan', $this->formatRupiahSingkat($saldoAwal))
                ->description('Total: ' . $this->formatRupiahFull($saldoAwal))
                ->chart([0, 0, 0])
                ->chartColor('primary')
                ->color('primary'),

            Stat::make('Pemasukan', $this->formatRupiahSingkat($pemasukan))
                ->description('Total: ' . $this->formatRupiahFull($pemasukan))
                ->chart([0, 0, 0])
                ->chartColor('success')
                ->color('success'),

            Stat::make('Pengeluaran', $this->formatRupiahSingkat($pengeluaran))
                ->description('Total: ' . $this->formatRupiahFull($pengeluaran))
                ->chart([0, 0, 0])
                ->chartColor('danger')
                ->color('danger'),

            Stat::make('Saldo Akhir', $this->formatRupiahSingkat($saldoAkhir))
                ->description('Total: ' . $this->formatRupiahFull($saldoAkhir))
                ->chart([0, 0, 0])
                ->chartColor('warning')
                ->color('warning'),
        ];
    }
}

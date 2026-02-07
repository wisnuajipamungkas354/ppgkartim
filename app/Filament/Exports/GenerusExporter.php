<?php

namespace App\Filament\Exports;

use App\Models\Generus;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use OpenSpout\Common\Entity\Style\Border;
use OpenSpout\Common\Entity\Style\BorderPart;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Options;

class GenerusExporter extends Exporter
{
    protected static ?string $model = Generus::class;

    public function getOptions(): array
    {

        return [
            (new Options)
            ->setColumnWidthForRange(40, 1, 12),
        ];
    }
    
    public function getXlsxHeaderCellStyle(): ?Style
    {
        return (new Style())
            ->setFontBold()
            ->setFontSize(12)
            ->setFontName('Times New Roman')
            ->setFontColor(Color::BLACK)
            ->setBackgroundColor(Color::BLUE)
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER)
            ->setBorder(new Border(new BorderPart(Border::BOTTOM, Color::BLACK, Border::WIDTH_THIN, Border::STYLE_SOLID)));
    }

    public function getXlsxCellStyle(): ?Style
    {
        return (new Style())
            ->setFontSize(11)
            ->setFontName('Times New Roman')
            ->setShouldWrapText(false);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('insan.daerah.nm_daerah'),
            ExportColumn::make('insan.desa.nm_desa'),
            ExportColumn::make('insan.kelompok.nm_kelompok'),
            ExportColumn::make('nis')
                ->label('NIS'),
            ExportColumn::make('insan.nama')
                ->label('Nama Lengkap'),
            ExportColumn::make('insan.jk')
                ->label('L/P'),
            ExportColumn::make('insan.kota_lahir')
                ->label('Kota Lahir'),
            ExportColumn::make('insan.tgl_lahir')
                ->label('Tanggal Lahir'),
            ExportColumn::make('insan.gol_dar')
                ->label('Golongan Darah'),
            ExportColumn::make('insan.usia')
                ->label('Usia'),
            ExportColumn::make('insan.pendidikan_terakhir')
                ->label('Pendidikan Terakhir'),
            ExportColumn::make('insan.jurusan')
                ->label('Jurusan'),
            ExportColumn::make('insan.siap_nikah')
                ->label('Siap Nikah'),
            ExportColumn::make('kelas_ppg.nm_kelas')
                ->label('Kelas di PPG'),
            ExportColumn::make('status.nm_status')
                ->label('Status'),
            ExportColumn::make('detail_status')
                ->label('Detail Status'),
                ExportColumn::make('insan.minat_bakat')
                ->label('Minat Bakat'),
            ExportColumn::make('aktif_mengajar'),
            ExportColumn::make('insan.nm_ayah')
                ->label('Nama Ayah'),
            ExportColumn::make('insan.nm_ibu')
                ->label('Nama Ibu'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your generus export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}

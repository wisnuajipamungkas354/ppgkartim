<?php

namespace App\Filament\Exports;

use App\Models\UangKas;
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

class UangKasExporter extends Exporter
{
    protected static ?string $model = UangKas::class;

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
            ExportColumn::make('tgl_transaksi')
                ->label('Tanggal Transaksi'),
            ExportColumn::make('nm_penginput')
                ->label('Nama Penginput'),
            ExportColumn::make('jenis_kas')
                ->label('Jenis Kas'),
            ExportColumn::make('nominal')
                ->label('Nominal'),
            ExportColumn::make('keterangan')
                ->label('Keterangan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your uang kas export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}

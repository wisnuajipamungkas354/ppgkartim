<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UangKasResource\Pages;
use App\Filament\Resources\UangKasResource\RelationManagers;
use App\Filament\Resources\UangKasResource\Widgets\KasOverview;
use App\Models\UangKas;
use App\Traits\HandlesActiveRolePermission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class UangKasResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = UangKas::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Kas';

    protected static ?string $navigationGroup = 'Database';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tgl_transaksi')
                    ->label('Tanggal')
                    ->default(date('d-m-Y'))
                    ->required(),
                Forms\Components\TextInput::make('nm_penginput')
                    ->label('Nama Penginput')
                    ->placeholder('Masukkan Nama Penginput')
                    ->maxLength(255),
                Forms\Components\Select::make('jenis_kas')
                    ->label('Jenis Kas')
                    ->options([
                        'PEMASUKAN' => 'Pemasukan',
                        'PENGELUARAN' => 'Pengeluaran'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('nominal')
                    ->prefix('Rp')
                    ->required()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\Textarea::make('keterangan')
                    ->placeholder('Masukkan Keterangan. Contoh : Snack Musyawaroh')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tgl_transaksi')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->default(date('d-m-Y'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('nm_penginput')
                    ->label('Penginput')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('keterangan'),
                Tables\Columns\TextColumn::make('jenis_kas')
                    ->label('Jenis Kas')
                    ->formatStateUsing(fn(string $state) => Str::title($state))
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'PEMASUKAN' => 'success',
                        'PENGELUARAN' => 'danger'
                    }),
                Tables\Columns\TextColumn::make('nominal')
                    ->formatStateUsing(fn(string $state, UangKas $record) => $record->jenis_kas === 'PEMASUKAN' ? '+Rp ' . number_format($state, 0, ',', '.'): '-Rp ' . number_format($state, 0, ',', '.'))
                    ->color(fn(string $state, UangKas $record) => match($record->jenis_kas) {
                        'PEMASUKAN' => 'success',
                        'PENGELUARAN' => 'danger'
                    })
                    ->alignEnd()
                    ->weight(FontWeight::SemiBold)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('filter')
                ->form([
                    Forms\Components\DatePicker::make('dari')
                        ->label('Dari'),
                    Forms\Components\DatePicker::make('sampai')
                        ->label('Sampai'),
                    Forms\Components\Select::make('jenis_kas')
                        ->placeholder('Pilih Jenis Kas')
                        ->options(['PEMASUKAN' => 'Pemasukan', 'PENGELUARAN' => 'Pengeluaran'])
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['dari'] ?? null,
                            fn (Builder $query, $dari): Builder => $query->where('tgl_transaksi', '>=', $dari),
                        )
                        ->when(
                            $data['sampai'] ?? null,
                            fn (Builder $query, $sampai): Builder => $query->where('tgl_transaksi', '<=', $sampai),
                        )
                        ->when(
                            $data['jenis_kas'] ?? null,
                            fn (Builder $query, $jenisKas): Builder => $query->where('jenis_kas', '=', $jenisKas),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['dari'] ?? null) {
                        $indicators['dari'] = 'Dari ' . $data['dari'];
                    }
                    if ($data['sampai'] ?? null) {
                        $indicators['sampai'] = 'Sampai ' .$data['sampai'];
                    }

                    return $indicators;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit Kas')
                    ->modalSubmitActionLabel('Simpan Perubahan')
                    ->modalCancelActionLabel('Batal'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data')
                    ->modalSubmitActionLabel('Hapus')
                    ->modalDescription('Apakah kamu yakin data kas ini dihapus ?')
                    ->modalCancelActionLabel('Batal')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada catatan kas')
            ->emptyStateDescription('Klik Tambah Data untuk menambah catatan keuangan')
            ->stripped();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUangKas::route('/'),
            'create' => Pages\CreateUangKas::route('/create'),
            'edit' => Pages\EditUangKas::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('tgl_transaksi', 'DESC');
    }
}

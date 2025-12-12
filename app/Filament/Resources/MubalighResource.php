<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MubalighResource\Pages;
use App\Filament\Resources\MubalighResource\RelationManagers;
use App\Models\Mubaligh;
use App\Traits\HandlesActiveRolePermission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MubalighResource extends Resource
{
    use HandlesActiveRolePermission;
    
    protected static ?string $model = Mubaligh::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Mubaligh';

    protected static ?string $navigationGroup = 'Database';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('insan_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('kategori')
                    ->required(),
                Forms\Components\TextInput::make('tingkatan_tugas'),
                Forms\Components\TextInput::make('asal_pondok')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tugasan_ke')
                    ->numeric(),
                Forms\Components\DatePicker::make('tgl_mulai_tugas'),
                Forms\Components\DatePicker::make('tgl_selesai_tugas'),
                Forms\Components\Toggle::make('selesai_tugas'),
                Forms\Components\TextInput::make('jml_tugas')
                    ->numeric(),
                Forms\Components\TextInput::make('lama_tugas')
                    ->maxLength(255),
                Forms\Components\Toggle::make('aktif_mengajar'),
                Forms\Components\TextInput::make('konfirmasi_kesiapan_tugas')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('insan.kelompok.nm_kelompok')
                    ->label('Kelompok')
                    ->sortable(),
                Tables\Columns\TextColumn::make('insan.nama')
                    ->label('Nama Lengkap')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori'),
                Tables\Columns\TextColumn::make('asal_pondok')
                    ->searchable(),
                Tables\Columns\TextColumn::make('insan.perkawinan')
                    ->label('Status')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListMubalighs::route('/'),
            'create' => Pages\CreateMubaligh::route('/create'),
            'edit' => Pages\EditMubaligh::route('/{record}/edit'),
        ];
    }
}

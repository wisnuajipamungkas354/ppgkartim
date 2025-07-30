<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelompokResource\Pages;
use App\Filament\Resources\KelompokResource\RelationManagers;
use App\Helpers\AccessHelper;
use App\Models\Desa;
use App\Models\Kelompok;
use App\Traits\HandlesActiveRolePermission;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelompokResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = Kelompok::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Kelompok';

    protected static ?string $navigationGroup = 'Manajemen Wilayah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nm_kelompok')
                    ->label('Nama Kelompok')
                    ->placeholder('Masukkan nama kelompok')
                    ->required(),
                TextInput::make('alias')
                    ->label('Alias')
                    ->placeholder('PAC X'),
                Select::make('desa_id')
                    ->label('Desa')
                    ->options(Desa::query()->pluck('nm_desa', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('nm_kelompok')
                    ->label('Nama Kelompok'),
                TextColumn::make('alias')
                    ->label('Alias'),
                TextColumn::make('desa.nm_desa')
                    ->label('Desa'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->label('Hapus')
                    ->modalHeading('Hapus Data')
                    ->modalDescription('Apakah kamu yakin ingin menghapus kelompok ini ?')
                    ->modalSubmitActionLabel('Ya')
                    ->modalCancelActionLabel('Batal')
                    ->successNotification(fn(Notification $notification) => $notification->title('Dihapus')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageKelompoks::route('/'),
        ];
    }
}

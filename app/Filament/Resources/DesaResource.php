<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DesaResource\Pages;
use App\Filament\Resources\DesaResource\RelationManagers;
use App\Helpers\AccessHelper;
use App\Models\Daerah;
use App\Models\Desa;
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

class DesaResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = Desa::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationLabel = 'Desa';

    protected static ?string $navigationGroup = 'Manajemen Wilayah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextColumn::make('id'),
                TextInput::make('nm_desa')
                    ->label('Nama Desa')
                    ->required(),
                TextInput::make('alias')
                    ->label('Alias')
                    ->placeholder('PC X'),
                Select::make('daerah_id')
                    ->label('Daerah')
                    ->options(Daerah::query()->pluck('nm_daerah', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('nm_desa')
                    ->label('Nama Desa'),
                TextColumn::make('alias')
                    ->label('Alias'),
                TextColumn::make('daerah.nm_daerah')
                    ->label('Daerah')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data')
                    ->modalDescription('Apakah kamu yakin ingin menghapus desa ini ?')
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
            'index' => Pages\ManageDesas::route('/'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DaerahResource\Pages;
use App\Filament\Resources\DaerahResource\RelationManagers;
use App\Helpers\AccessHelper;
use App\Models\Daerah;
use App\Traits\HandlesActiveRolePermission;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DaerahResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = Daerah::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Daerah';

    protected static ?string $navigationGroup = 'Manajemen Wilayah';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextColumn::make('id'),
                TextInput::make('nm_daerah')
                    ->label('Nama Daerah')
                    ->required(),
                TextInput::make('alias')
                    ->label('Alias')
                    ->placeholder('Masukkan nama DPD')                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID'),
                TextColumn::make('nm_daerah')
                    ->label('Nama Daerah'),
                TextColumn::make('alias')
                    ->label('Alias'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data')
                    ->modalDescription('Menghapus daerah, berarti menghapus semua data desa, kelompok, generus, dan hal-hal lain yang berkaitan dengannya. Apakah kamu yakin ?')
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
            'index' => Pages\ManageDaerahs::route('/'),
        ];
    }
}

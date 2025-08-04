<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasPpgResource\Pages;
use App\Filament\Resources\KelasPpgResource\RelationManagers;
use App\Models\KelasPpg;
use App\Traits\HandlesActiveRolePermission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KelasPpgResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = KelasPpg::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kelas PPG';

    protected static ?string $navigationGroup = 'Manajemen List';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nm_kelas')
                    ->label('Nama Kelas')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('nm_kelas')
                    ->label('Nama Kelas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Data')
                    ->modalDescription('Apakah kamu yakin ingin menghapus kelas ini ?')
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
            'index' => Pages\ManageKelasPpgs::route('/'),
        ];
    }
}

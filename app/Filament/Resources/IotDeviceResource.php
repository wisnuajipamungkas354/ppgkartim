<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IotDeviceResource\Pages;
use App\Filament\Resources\IotDeviceResource\RelationManagers;
use App\Models\IotDevice;
use App\Traits\HandlesActiveRolePermission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IotDeviceResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = IotDevice::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    
    protected static ?string $navigationGroup = 'Master Data';
    
    protected static ?string $navigationLabel = 'IoT Devices';
    
    protected static ?string $modelLabel = 'IoT Device';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Pemilik Device (User)')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Device')
                    ->required(),
                Forms\Components\TextInput::make('api_token')
                    ->label('API Token')
                    ->default(fn () => \Illuminate\Support\Str::random(40))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->readOnly()
                    ->helperText('Token ini digunakan untuk autentikasi API pada ESP32. Copy token ini.'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Device')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('api_token')
                    ->label('API Token')
                    ->copyable()
                    ->copyMessage('Token berhasil disalin')
                    ->limit(15),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListIotDevices::route('/'),
            'create' => Pages\CreateIotDevice::route('/create'),
            'edit' => Pages\EditIotDevice::route('/{record}/edit'),
        ];
    }
}

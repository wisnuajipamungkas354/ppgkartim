<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleAccessResource\Pages;
use App\Filament\Resources\RoleAccessResource\RelationManagers;

use App\Models\RoleResourceAccess;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleAccessResource extends Resource
{
    protected static ?string $model = RoleResourceAccess::class;

    protected static ?string $navigationLabel = 'Akses Role ke Resource';
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationGroup = 'Manajemen Akses';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('role_id')
                    ->relationship('role', 'name')
                    ->label('Role')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('resource_id')
                    ->relationship('resource', 'name')
                    ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('role.name')->label('Role'),
            Tables\Columns\TextColumn::make('resource.slug')->label('Resource'),
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
            'index' => Pages\ListRoleAccesses::route('/'),
            'create' => Pages\CreateRoleAccess::route('/create'),
            'edit' => Pages\EditRoleAccess::route('/{record}/edit'),
        ];
    }
}

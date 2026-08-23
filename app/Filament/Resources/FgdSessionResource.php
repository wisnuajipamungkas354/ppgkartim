<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FgdSessionResource\Pages;
use App\Models\FgdSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Traits\HandlesActiveRolePermission;

class FgdSessionResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = FgdSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'FGD Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Sesi FGD')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Nama Sesi FGD')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->label('Tanggal Pelaksanaan'),
                        Forms\Components\Toggle::make('status')
                            ->required()
                            ->label('Status Aktif')
                            ->default(true),
                    ]),

                Forms\Components\Section::make('Kelompok FGD')
                    ->schema([
                        Forms\Components\Repeater::make('groups')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->label('Nama Kelompok (Contoh: Kelompok 1)')
                                    ->maxLength(255),
                            ])
                            ->columns(1)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Kelompok'),
                    ]),

                Forms\Components\Section::make('Tema Pembahasan FGD')
                    ->schema([
                        Forms\Components\Repeater::make('themes')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->label('Judul Tema')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi Singkat (Opsional)')
                                    ->maxLength(65535),
                            ])
                            ->columns(1)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Tema'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Sesi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('groups_count')
                    ->counts('groups')
                    ->label('Jml Kelompok'),
                Tables\Columns\TextColumn::make('themes_count')
                    ->counts('themes')
                    ->label('Jml Tema'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFgdSessions::route('/'),
            'create' => Pages\CreateFgdSession::route('/create'),
            'edit' => Pages\EditFgdSession::route('/{record}/edit'),
        ];
    }
}

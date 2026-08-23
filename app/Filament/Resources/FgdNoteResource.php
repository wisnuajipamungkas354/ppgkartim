<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FgdNoteResource\Pages;
use App\Models\FgdNote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Traits\HandlesActiveRolePermission;

class FgdNoteResource extends Resource
{
    use HandlesActiveRolePermission;

    protected static ?string $model = FgdNote::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'FGD Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Notulis')
                    ->schema([
                        Forms\Components\Select::make('fgd_group_id')
                            ->relationship('group', 'name')
                            ->label('Grup')
                            ->disabled(),
                        Forms\Components\Select::make('fgd_theme_id')
                            ->relationship('theme', 'title')
                            ->label('Tema')
                            ->disabled(),
                        Forms\Components\TextInput::make('notulis_name')
                            ->label('Nama Notulis')
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('1. Problem - Penyebab - Solusi')
                    ->schema([
                        Forms\Components\RichEditor::make('problem')->disabled(),
                        Forms\Components\RichEditor::make('penyebab')->disabled(),
                        Forms\Components\RichEditor::make('solusi')->disabled(),
                    ]),

                Forms\Components\Section::make('2. Action Plan')
                    ->schema([
                        Forms\Components\RichEditor::make('ap_deskripsi')->label('Deskripsi')->disabled(),
                        Forms\Components\RichEditor::make('ap_nama_kegiatan')->label('Nama Kegiatan')->disabled(),
                        Forms\Components\RichEditor::make('ap_peserta')->label('Peserta')->disabled(),
                        Forms\Components\RichEditor::make('ap_waktu')->label('Waktu')->disabled(),
                        Forms\Components\RichEditor::make('ap_dana')->label('Dana')->disabled(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group.name')
                    ->label('Grup')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('theme.title')
                    ->label('Tema')
                    ->sortable()
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('notulis_name')
                    ->label('Notulis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Submit')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListFgdNotes::route('/'),
            'view' => Pages\ViewFgdNote::route('/{record}'),
        ];
    }
}

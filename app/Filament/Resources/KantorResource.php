<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KantorResource\Pages;
use App\Filament\Resources\KantorResource\RelationManagers;
use App\Models\Kantor;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KantorResource extends Resource
{
    protected static ?string $model = Kantor::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Kantor';

    protected static ?string $pluralLabel = 'kantor';

    protected static ?string $slug = 'kantor';

    protected static ?string $navigationGroup = 'ekspedisi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kantor')
                    ->required()
                    ->label('Kantor/Tempat Tujuan')
                    ->autocomplete('off')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ekspedisi')
                    ->required()
                    ->autocomplete('off')
                    ->maxLength(255),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->label('Alamat Tujuan')
                    ->autocomplete('off')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kantor'),
                Tables\Columns\TextColumn::make('ekspedisi'),
                Tables\Columns\TextColumn::make('alamat'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListKantors::route('/'),
            'create' => Pages\CreateKantor::route('/create'),
            'edit' => Pages\EditKantor::route('/{record}/edit'),
        ];
    }    
}

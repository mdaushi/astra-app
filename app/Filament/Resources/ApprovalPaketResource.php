<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use App\Models\ApprovalPaket;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ApprovalPaketResource\Pages;
use App\Filament\Resources\ApprovalPaketResource\RelationManagers;

class ApprovalPaketResource extends Resource
{
    protected static ?string $model = ApprovalPaket::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Approval';

    protected static ?string $pluralLabel = 'Approval';

    protected static ?string $slug = 'approval-paket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('approval_1')
                    ->relationship('approvalsatu', 'nama')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nama} - {$record->jabatan->nama}"),
                Forms\Components\Select::make('approval_2')
                    ->relationship('approvaldua', 'nama')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nama} - {$record->jabatan->nama}"),
                Forms\Components\Select::make('approval_3')
                    ->relationship('approvaltiga', 'nama')
                    ->preload()
                    ->searchable()
                    ->required()
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nama} - {$record->jabatan->nama}"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode'),
                Tables\Columns\TextColumn::make('approvalsatu.nama')
                    ->label('Approval 1'),
                Tables\Columns\TextColumn::make('approvaldua.nama')
                    ->label('Approval 2'),
                Tables\Columns\TextColumn::make('approvaltiga.nama')
                    ->label('Approval 3'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListApprovalPakets::route('/'),
            'create' => Pages\CreateApprovalPaket::route('/create'),
            'edit' => Pages\EditApprovalPaket::route('/{record}/edit'),
        ];
    }    
}

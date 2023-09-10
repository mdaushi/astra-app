<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Pegawai;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Spatie\Permission\Models\Role;
use App\Filament\Resources\PegawaiResource\Pages;
use App\Filament\Resources\PegawaiResource\Pages\EditPegawai;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class PegawaiResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';


    protected static ?string $navigationLabel = 'Pegawai';

    protected static ?string $pluralLabel = 'Pegawai';

    protected static ?string $navigationGroup = 'pegawai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->disableAutocomplete()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('whatsapp')
                    ->disableAutoComplete()
                    ->required()
                    ->placeholder('62xxxx')
                    ->numeric(),
                Forms\Components\TextInput::make('npk')
                    ->label('NPK')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('golongan_id')
                    ->label('Golongan')
                    ->required()
                    ->relationship('golongan', 'nama')
                    ->preload()
                    ->searchable(),
                Forms\Components\Select::make('jabatan')
                    ->label('Jabatan')
                    ->required()
                    ->multiple()
                    ->relationship('jabatan', 'nama')
                    ->preload(),
                Forms\Components\TextInput::make('kode_area')
                    ->label('Kode Area')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('area')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Section::make('Ekspedisi')
                    ->description('Pegawai ini mengirim barang dengan jenis Faktur/ tujuan dan alamat yang sama setiap harinya')
                    ->schema([
                        Forms\Components\Toggle::make('is_faktur_ekspedisi')
                            ->onIcon('heroicon-s-lightning-bolt')
                            ->offIcon('heroicon-s-user')
                            ->label('Faktur'),
                    ])
                    ->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('npk'),
                Tables\Columns\TextColumn::make('golongan.nama'),
                Tables\Columns\TextColumn::make('jabatan.nama'),
                Tables\Columns\TextColumn::make('kode_area'),
                Tables\Columns\TextColumn::make('area'),
                
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
}

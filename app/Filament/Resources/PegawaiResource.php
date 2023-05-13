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
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PegawaiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PegawaiResource\RelationManagers;
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
                Forms\Components\Select::make('jabatan_id')
                    ->label('Jabatan')
                    ->required()
                    ->relationship('jabatan', 'nama')
                    ->preload()
                    ->searchable(),
                Forms\Components\TextInput::make('kode_area')
                    ->label('Kode Area')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('area')
                    ->required()
                    ->maxLength(255),
                // Forms\Components\TextInput::make('user_id')
                //     ->required(),
                // Forms\Components\FileUpload::make('picture')
                //     ->required()
                //     ->image()
                //     ->disk('public')
                //     ->directory('picture')
                //     ->preserveFilenames(),
                // Forms\Components\Select::make('approvals_id')
                //     ->label('Approvals')
                //     // ->required()
                //     ->relationship('approvalPaket', 'kode')
                //     ->preload()
                //     ->searchable(),
                Forms\Components\Section::make('Akun')
                    ->description('Pembuatan akun diperluhkan untuk dapat mengakses aplikasi ini')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->unique(table: User::class)
                            ->required()
                            ->hidden(fn (Page $livewire) => $livewire instanceof EditPegawai)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255)
                            ->hidden(fn (Page $livewire) => $livewire instanceof EditPegawai),
                        Forms\Components\Select::make('role')
                            ->required()
                            ->options(Role::all()->pluck('name', 'id'))
                            ->searchable()
                    ])
                    ->columns(2),
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
                // Tables\Columns\ImageColumn::make('picture'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
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

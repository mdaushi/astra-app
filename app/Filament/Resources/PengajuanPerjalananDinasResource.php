<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Golongan;
use App\Models\Rekening;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use App\Models\PengajuanPerjalananDinas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PengajuanPerjalananDinasResource\Pages;
use App\Filament\Resources\PengajuanPerjalananDinasResource\RelationManagers;
use App\Filament\Resources\PengajuanPerjalananDinasResource\RelationManagers\KegiatanPerjalananDinasRelationManager;

class PengajuanPerjalananDinasResource extends Resource
{
    protected static ?string $model = PengajuanPerjalananDinas::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Form Pengajuan')
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama \ NPK')
                                    ->required()
                                    ->maxLength(255),
                                // Forms\Components\Select::make('golongan')
                                //     ->required()
                                //     ->options(Golongan::all()->pluck('nama', 'nama'))
                                //     ->searchable()
                                //     ->preload(),
                                Forms\Components\Select::make('penginapan')
                                    ->options([
                                        'hotel' => 'Hotel',
                                        'rumah sendiri' => 'Rumah Sendiri',
                                        'other' => 'Other',
                                    ])
                                    ->required()
                                    ->searchable(),
                                Forms\Components\Select::make('payment')
                                    ->options([
                                        'tunai' => 'Tunai',
                                        'transfer' => 'Transfer',
                                    ])
                                    ->required()
                                    ->searchable(),
                                // Forms\Components\Select::make('bank')
                                //     ->required()
                                //     ->relationship('rekening', 'rekening')
                                //     ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->nama} - {$record->rekening}")
                                //     // ->options(Rekening::where('pegawai_id', auth()->user()->pegawai->id)->pluck('nama', 'id'))
                                //     ->preload()
                                //     ->searchable(),
                            ])
                            ->columns(2),
                        Forms\Components\Wizard\Step::make('Kegiatan/Agenda')
                            ->schema([
                                Forms\Components\Repeater::make('kegiatan')
                                    ->label('Kegiatan')
                                    ->schema([
                                        Forms\Components\DatePicker::make('tanggal')
                                            ->displayFormat('d/m/Y')
                                            ->required(),
                                        Forms\Components\TextInput::make('dari_kota')
                                            ->label('Dari Kota')
                                            ->maxLength(255)
                                            ->required(),
                                        Forms\Components\TextInput::make('ke_kota')
                                            ->label('Ke Kota')
                                            ->maxLength(255)
                                            ->required(),
                                        Forms\Components\TextInput::make('kegiatan_pokok')
                                            ->label('Kegiatan Pokok')
                                            ->maxLength(255)
                                            ->required(),
                                        Forms\Components\TimePicker::make('berangkat_jam')
                                            ->label('Jam Berangkat')
                                            ->required(),
                                        Forms\Components\TimePicker::make('tiba_jam')
                                            ->label('Jam Tiba')
                                            ->required(),
                                        Forms\Components\TextInput::make('maskapai')
                                            ->maxLength(255)
                                            ->required(),
                                            Forms\Components\Select::make('payment_via')
                                            ->label('Payment Via')
                                            ->options([
                                                // '    ' => 'Beli Sendiri',
                                                'ga' => 'GA'
                                            ])
                                            ->searchable()
                                            ->required(),
                                    ])
                                    ->columns(2)
                            ])
                ])  
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_surat'),
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('golongan'),
                Tables\Columns\TextColumn::make('penginapan'),
                Tables\Columns\TextColumn::make('payment'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('biaya')
                ->url(fn (PengajuanPerjalananDinas $record): string => route('filament.resources.pengajuan-perjalanan-dinas.biaya', $record)),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    // Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListPengajuanPerjalananDinas::route('/'),
            'create' => Pages\CreatePengajuanPerjalananDinas::route('/create'),
            'edit' => Pages\EditPengajuanPerjalananDinas::route('/{record}/edit'),
            'view' => Pages\ViewPengajuanPerjalananDinas::route('/{record}/view'),
            'biaya' => Pages\EstimasiBiayaPengajuanPerjalananDinas::route('/{record}/biaya')
        ];
    }
}

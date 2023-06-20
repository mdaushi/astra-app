<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Kantor;
use App\Models\Wilayah;
use App\Models\Ekspedisi;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\FakturEkspedisi;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Layout;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EkspedisiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EkspedisiResource\RelationManagers;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class EkspedisiResource extends Resource
{
    protected static ?string $model = Ekspedisi::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Kirim Barang';

    protected static ?string $pluralLabel = 'ekspedisi';

    protected static ?string $slug = 'ekspedisi';

    protected static ?string $navigationGroup = 'ekspedisi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('kategori')
                    ->required()
                    ->options([
                        'nonfaktur' => 'Non Faktur',
                        'faktur' => 'Faktur',
                    ])
                    ->default('nonfaktur')
                    ->helperText('untuk kategori faktur, pastika anda telah mengisi data faktur pada menu Profile')
                    ->reactive()
                    ->afterStateUpdated(function($state, callable $set){
                        if($state == 'faktur') {
                            // $faktur = FakturEkspedisi::where('pegawai_id', auth()->user()->pegawai->id)
                            //     ->first();
                            // $set('tempat_tujuan', $faktur->tempat_tujuan ?? '');
                            // $set('alamat_tujuan', $faktur->alamat_tujuan ?? '');
                            return $set('jenis_paket', 'Faktor');
                        } 
                        return $set('jenis_paket', null);
                        
                    })
                    ->searchable(),
                Forms\Components\Select::make('wilayah')
                    ->required()
                    ->options(Wilayah::all()->pluck('wilayah', 'wilayah'))
                    ->searchable()
                    ->reactive()
                    ->hidden(function(callable $get){
                        if($get('kategori') == 'faktur'){
                            return true;
                        }

                        return false;
                    })
                    ->afterStateUpdated(function($state, callable $set) {
                        $set('ekspedisi', Wilayah::where('wilayah', $state)->first()->ekspedisi ?? '');
                    }),
                Forms\Components\TextInput::make('ekspedisi')
                    ->required()
                    ->disabled(),

                Forms\Components\DatePicker::make('tanggal')
                    ->required(),

                // Kantor/Tempat Tujuan
                Forms\Components\TextInput::make('tempat_tujuan_nonfaktur')
                    ->label('Kantor/Tempat Tujuan')
                    ->required()
                    ->hidden(function(callable $get){
                        if($get('kategori') == 'faktur'){
                            return true;
                        }
                        return false;
                    })
                    ->maxLength(255),

                Forms\Components\Select::make('tempat_tujuan_faktur')
                    ->options(Kantor::all()->pluck('kantor', 'kantor'))
                    ->label('Kantor/Tempat Tujuan')
                    ->hidden(function(callable $get){
                        if($get('kategori') != 'faktur'){
                            return true;
                        }
                        return false;
                    })
                    ->reactive()
                    ->searchable()
                    ->afterStateUpdated(function($state, callable $set){
                        $kantor = Kantor::where('kantor', $state)->first();
                        $set('ekspedisi', $kantor['ekspedisi'] ?? '');
                        $set('alamat_tujuan', $kantor['alamat'] ?? '');
                    })
                    ->required(),

                // alamat tujuan
                Forms\Components\TextInput::make('alamat_tujuan')
                    ->required()
                    ->disabled(function(callable $get){
                        if($get('kategori') == 'faktur'){
                            return true;
                        }
                        return false;
                    })
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_penerima')
                    ->required()
                    ->autocomplete('off')
                    ->hidden(function(callable $get) {
                        if($get('kategori') == 'faktur'){
                            return true;
                        }
                        return false;
                    })
                    ->label('Nama Penerima'),
                Forms\Components\TextInput::make('jumlah_dokumen')
                    ->required()
                    ->autocomplete('off')
                    ->hidden(function(callable $get) {
                        if($get('kategori') == 'nonfaktur'){
                            return true;
                        }
                        return false;
                    })
                    ->numeric()
                    ->label('Jumlah Dokumen'),
                Forms\Components\TextInput::make('kontak')
                    ->label('Kontak yang bisa dihubungi')
                    ->hidden(function(callable $get) {
                        if($get('kategori') == 'faktur'){
                            return true;
                        }
                        return false;
                    })
                    ->maxLength(255),
                Forms\Components\TextInput::make('nama_pengirim')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('jenis_paket')
                    ->required()
                    ->disabled(function(callable $get){
                        if($get('kategori') == 'faktur'){
                            return true;
                        }

                        return false;
                    })
                    ->maxLength(255),
                Forms\Components\select::make('jenis_layanan')
                    ->required()
                    ->options([
                        // 'yes' => 'YES',
                        // 'ods' => 'ODS',
                        'reguler' => 'REGULER',
                        'other' => 'Other'
                    ])
                    ->searchable()
                    ->hidden(function(callable $get) {
                        if($get('kategori') == 'faktur'){
                            return true;
                        }
                        return false;
                    })
                    ->reactive(),
                Forms\Components\TextInput::make('alasan_jenis_layanan_other')
                    ->required()
                    ->hidden(fn (Closure $get) => $get('jenis_layanan') !== 'other')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('kategori'),
                // Tables\Columns\TextColumn::make('ekspedisi'),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date(),
                Tables\Columns\TextColumn::make('tempat_tujuan')
                    ->label('Kantor/Tempat Tujuan'),
                Tables\Columns\TextColumn::make('alamat_tujuan'),
                Tables\Columns\TextColumn::make('nama_penerima')
                    ->label('Nama Penerima'),
                Tables\Columns\TextColumn::make('jumlah_dokumen')
                    ->label('Jumlah Dokumen'),
                Tables\Columns\TextColumn::make('kontak')
                    ->label('Kontak yang bisa dihubungi'),
                Tables\Columns\TextColumn::make('nama_pengirim'),
                Tables\Columns\TextColumn::make('jenis_paket'),
                Tables\Columns\TextColumn::make('jenis_layanan'),
                Tables\Columns\TextColumn::make('no_resi'),
                Tables\Columns\TextColumn::make('status')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('resi')
                    ->label('Upload Resi')
                    ->icon('heroicon-s-pencil')
                    ->requiresConfirmation()
                    ->action(function (Ekspedisi $record, array $data): void {
                        $record->update([
                            'no_resi' => $data['no_resi']
                        ]);
                        
                        Notification::make() 
                            ->title('Saved successfully')
                            ->success()
                            ->send(); 
                    })
                    ->form([
                        Forms\Components\TextInput::make('no_resi')
                            ->label('No Resi')
                            ->required(),
                    ])
                    ->visible(fn (Ekspedisi $record): bool => auth()->user()->can('resi_ekspedisi', $record)),
                Tables\Actions\Action::make('status')
                    ->label('Update Status')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'menunggu dipickup' => 'Menunggu dipickup',
                                'sudah dipickup' => 'Sudah dipickup'
                            ])
                    ])
                    ->action(function (Ekspedisi $record, array $data): void {
                        $record->update([
                            'status' => $data['status']
                        ]);

                        Notification::make() 
                            ->title('Saved successfully')
                            ->success()
                            ->send(); 
                    })
                    ->visible(fn (Ekspedisi $record): bool => auth()->user()->can('resi_ekspedisi', $record)),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])->headerActions([
                FilamentExportHeaderAction::make('export')
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
            'index' => Pages\ListEkspedisis::route('/'),
            'create' => Pages\CreateEkspedisi::route('/create'),
            'edit' => Pages\EditEkspedisi::route('/{record}/edit'),
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
            'resi'
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if(auth()->user()->roles[0]->name == 'kurir'){
            return static::getModel()::query()->where('ekspedisi', strtolower(auth()->user()->name));
        }

        if(auth()->user()->roles[0]->name != 'admin'){
            return static::getModel()::query()->where('pegawai_id', auth()->user()->pegawai->id);
        }

        return static::getModel()::query();
    }
}

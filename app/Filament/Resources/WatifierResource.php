<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WatifierResource\Actions\StartConnectAction;
use Filament\Forms;
use Filament\Tables;
use App\Models\watifier;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use App\Filament\Resources\WatifierResource\Pages;

class WatifierResource extends Resource
{
    protected static ?string $model = watifier::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->contentGrid([
            'md' => 2,
            'xl' => 3,
        ])
            ->columns([
                Tables\Columns\ImageColumn::make('')
                    ->defaultImageUrl(url('img/WhatsApp.svg.webp')),
                Tables\Columns\TextColumn::make('value')->label('device'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('cek_koneksi')
                    ->action(function(watifier $wa){
                        $status = $wa->statusSession();

                        // error
                        if($status['error']){
                            return Notification::make()
                            ->title('Whatsapp '.$status['message'])
                            ->warning()
                            ->send();
                        }

                        // jika belum terkoneksi
                        if(!$status['instance_data']['user']){
                            return Notification::make()
                            ->title('Whatsapp belum terkoneksi')
                            ->warning()
                            ->send(); 
                        }

                        Notification::make()
                            ->title('Whatsapp terkoneksi')
                            ->success()
                            ->send();
                        
                    }),
                StartConnectAction::make(),
            ])
            ->bulkActions([
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWatifiers::route('/'),
        ];
    }    
}

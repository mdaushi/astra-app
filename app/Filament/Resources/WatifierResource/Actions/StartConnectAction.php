<?php

namespace App\Filament\Resources\WatifierResource\Actions;

use Filament\Tables\Actions\Action;
use Closure;
use Filament\Forms\ComponentContainer;
use Illuminate\Database\Eloquent\Model;

class StartConnectAction extends Action 
{
    protected ?Closure $mutateRecordDataUsing = null;


    public static function getDefaultName(): ?string
    {
        return 'Mulai';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Mulai');

        $this->modalHeading(fn (): string => __('filament-support::actions/view.single.modal.heading', ['label' => 'Koneksi']));

        $this->modalActions(fn (): array => array_merge(
            $this->getExtraModalActions(),
            [$this->getModalCancelAction()->label(__('filament-support::actions/view.single.modal.actions.close.label'))],
        ));

        // $this->color('secondary');

        $this->icon('heroicon-s-link');

        $this->action(static function (): void {            
        });

        $this->modalContent(view('filament.resources.watifier.actions.connect'));

    }

    
}
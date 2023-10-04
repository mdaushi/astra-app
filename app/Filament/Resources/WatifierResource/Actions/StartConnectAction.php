<?php

namespace App\Filament\Resources\WatifierResource\Actions;

use App\Models\watifier;
use Filament\Tables\Actions\Action;
use Closure;

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

        $this->modalHeading('Scan QRCode dibawah untuk menghubungkan perangkat');

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
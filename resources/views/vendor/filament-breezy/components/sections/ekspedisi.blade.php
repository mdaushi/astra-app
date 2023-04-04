<x-filament-breezy::grid-section class="mt-8">

    <x-slot name="title">
        Faktur Ekspedisi
    </x-slot>

    <x-slot name="description">
        Informasi untuk pengiriman barang
    </x-slot>

    <form wire:submit.prevent="updateFaktur" class="col-span-2 sm:col-span-1 mt-5 md:mt-0">
        <x-filament::card>

            {{ $this->updateFakturForm }}

            <x-slot name="footer">
                <div class="text-right">
                    <x-filament::button type="submit">
                        {{ __('filament-breezy::default.profile.personal_info.submit.label') }}
                    </x-filament::button>
                </div>
            </x-slot>
        </x-filament::card>
    </form>

</x-filament-breezy::grid-section>

<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}
        <div class="gap-2.5" style="display:flex; margin-top:50px">
            <x-filament::button type="submit" color="primary">
                {{ $this->getTitleButtonSubmit() }}
            </x-filament::button>
            <x-filament::button color="gray" wire:click="cancel">
                {{ $this->getTitleButtonCancel() }}
            </x-filament::button>

        </div>
    </form>
</x-filament-panels::page>

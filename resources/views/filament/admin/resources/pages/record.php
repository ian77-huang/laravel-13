<x-filament-panels::page>
    <x-filament-schemas::form wire:submit="submit">
        {{ $this->form }}

        <x-filament::button type="submit">
            發送
        </x-filament::button>
    </x-filament-schemas::form>
</x-filament-panels::page>

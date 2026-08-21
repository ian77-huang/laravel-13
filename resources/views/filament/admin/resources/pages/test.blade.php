<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}
        12345
        <div class="mt-4 flex justify-end">
            <x-filament::button type="submit" color="primary">
                送出
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>

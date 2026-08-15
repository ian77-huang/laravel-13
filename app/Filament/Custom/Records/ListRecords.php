<?php

namespace App\Filament\Custom\Records;

use App\Filament\Custom\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords as FilamentListRecords;

abstract class ListRecords extends FilamentListRecords
{
    protected static array $transKeys = [];

    public function getBreadcrumb(): ?string
    {
        return __(static::$transKeys['breadcrumb'] ?? 'Missing Group');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('button.create')),
        ];
    }
}

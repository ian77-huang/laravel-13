<?php

namespace App\Filament\Components\Records;

use App\Filament\Components\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords as FilamentListRecords;

abstract class ListRecords extends FilamentListRecords
{
    abstract public function getCustomBreadcrumb(): ?string;

    public function getBreadcrumb(): ?string
    {
        return $this->getCustomBreadcrumb();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('button.create')),
        ];
    }
}

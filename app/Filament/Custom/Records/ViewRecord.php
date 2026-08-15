<?php

namespace App\Filament\Custom\Records;

use App\Filament\Custom\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord as FilamentViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewRecord extends FilamentViewRecord
{
    public function getBreadcrumb(): string
    {
        return __('button.view');
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament.title.page.view', ['label' => $this->getRecordTitle()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(__('button.edit')),
        ];
    }
}

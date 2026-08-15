<?php

namespace App\Filament\Custom\Records;

use App\Filament\Custom\Actions\DeleteAction;
use App\Filament\Custom\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord as FilamentEditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditRecord extends FilamentEditRecord
{
    public function getBreadcrumb(): string
    {
        return __('button.edit');
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament.title.page.edit', ['label' => $this->getRecordTitle()]);
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()->label(__('button.save'));
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()->label(__('button.cancel'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label(__('button.view')),
            DeleteAction::make()
                ->label(__('button.delete')),
        ];
    }
}

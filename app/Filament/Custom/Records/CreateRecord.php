<?php

namespace App\Filament\Custom\Records;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord as FilamentCreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateRecord extends FilamentCreateRecord
{
    public function getBreadcrumb(): string
    {
        return __('button.create');
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament.title.page.create', ['label' => static::getResource()::getTitleCaseModelLabel()]);
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()->label(__('button.create'));
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()->label(__('button.create_and_create_another'));
    }
}

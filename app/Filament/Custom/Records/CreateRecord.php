<?php

namespace App\Filament\Custom\Records;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord as FilamentCreateRecord;

class CreateRecord extends FilamentCreateRecord
{
    protected function setUp(): void
    {
        parent::setUp();
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

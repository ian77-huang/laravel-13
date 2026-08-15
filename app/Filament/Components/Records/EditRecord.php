<?php

namespace App\Filament\Components\Records;

use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord as FilamentEditRecord;

class EditRecord extends FilamentEditRecord
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()->label(__('button.save'));
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()->label(__('button.cancel'));
    }
}

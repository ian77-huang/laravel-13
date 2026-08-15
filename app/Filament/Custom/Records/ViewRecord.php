<?php

namespace App\Filament\Custom\Records;

use App\Filament\Custom\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord as FilamentViewRecord;

class ViewRecord extends FilamentViewRecord
{
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(__('button.edit')),
        ];
    }
}

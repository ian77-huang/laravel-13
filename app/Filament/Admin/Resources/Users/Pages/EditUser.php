<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Custom\Actions\DeleteAction;
use App\Filament\Custom\Actions\ViewAction;
use App\Filament\Custom\Records\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

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

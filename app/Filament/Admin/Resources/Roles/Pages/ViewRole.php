<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Services\PermissionService;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(__('button.edit')),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $modulePermissions = app(PermissionService::class)
            ->formatPermissionsByModule($this->record->permissions);

        return array_merge($data, $modulePermissions);
    }
}

<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Custom\Records\ViewRecord;
use App\Services\PermissionService;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $modulePermissions = app(PermissionService::class)
            ->formatPermissionsByModule($this->record->permissions);

        return array_merge($data, $modulePermissions);
    }
}

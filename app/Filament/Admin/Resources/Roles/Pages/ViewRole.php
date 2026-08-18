<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Custom\Records\ViewRecord;
use App\Services\PermissionService;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected static array $transKeys = [
        'breadcrumbs' => ['front' => 'role.role', 'back' => 'button.view'],
    ];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return app(PermissionService::class)->formatPermissionsForForm($data, $this->record);
    }
}

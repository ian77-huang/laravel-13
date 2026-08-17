<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Custom\Records\ViewRecord;
use App\Filament\Admin\Resources\Roles\Supports\Support;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return Support::formatPermissionsForForm($data, $this->record);
    }
}

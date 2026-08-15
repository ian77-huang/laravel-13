<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Custom\Records\EditRecord;
use App\Services\PermissionService;
use Illuminate\Contracts\Support\Htmlable;
use Spatie\Permission\Models\Permission;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    // public function getHeading(): string|Htmlable|null
    // {
    //     return '自訂的標題';
    // }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $guardName = $data['guard_name'] ?? $this->record->guard_name ?? 'web';

        $permissions = [];
        foreach ($data as $module => $actions) {
            if (in_array($module, ['name', 'guard_name']) || ! is_array($actions)) {
                continue;
            }

            foreach ($actions as $action) {
                $permissionName = "{$module}.{$action}";

                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => $guardName,
                ]);

                $permissions[] = $permissionName;
            }
        }

        $this->record->syncPermissions($permissions);

        foreach (array_keys(config('permissions.modules')) as $module) {
            unset($data[$module]);
        }

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $modulePermissions = app(PermissionService::class)
            ->formatPermissionsByModule($this->record->permissions);

        return array_merge($data, $modulePermissions);
    }
}

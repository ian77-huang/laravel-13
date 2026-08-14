<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $defaultGuards = array_keys(config('auth.guards'));

        if (! in_array($data['guard_name'], $defaultGuards, true)) {
            $data['guard_name'] = 'web';
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $guardName = $data['guard_name'] ?? 'web';

        $record = Role::firstOrCreate([
            'name' => $data['name'],
            'guard_name' => $guardName,
        ]);

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

        $record->syncPermissions($permissions);

        return $record;
    }

    protected function afterCreate(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

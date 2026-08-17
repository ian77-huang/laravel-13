<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Custom\Records\CreateRecord;
use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return app(PermissionService::class)->validRoleData($data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $record = null;

            foreach ($data['permissions'] as $guardName => $modules) {
                $record = Role::firstOrCreate([
                    'name' => $data['name'],
                    'guard_name' => $guardName,
                ]);

                $permissions = [];

                foreach ($modules as $module => $actions) {
                    foreach ($actions as $action) {
                        $permissionName = "{$module}.{$action}";

                        $permission = Permission::firstOrCreate([
                            'name' => $permissionName,
                            'guard_name' => $guardName,
                        ]);

                        $permissions[] = $permission;
                    }
                }

                $record->syncPermissions($permissions);
            }

            return $record;
        });
    }

    protected function afterCreate(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

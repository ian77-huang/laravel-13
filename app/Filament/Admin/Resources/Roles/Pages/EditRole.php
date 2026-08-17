<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Admin\Resources\Roles\Supports\Support;
use App\Filament\Custom\Records\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return Support::checkRoleData($data);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $guardName = $record->guard_name;
            $guardPermissions = $data['permissions'][$guardName] ?? [];

            $permissions = [];

            foreach ($guardPermissions as $module => $actions) {
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

            // Delete orphaned permissions for this guard
            $usedPermissionIds = DB::table('role_has_permissions')
                ->pluck('permission_id');

            Permission::where('guard_name', $guardName)
                ->whereNotIn('id', $usedPermissionIds)
                ->delete();

            return $record;
        });
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return Support::formatPermissionsForForm($data, $this->record);
    }

    protected function afterSave(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

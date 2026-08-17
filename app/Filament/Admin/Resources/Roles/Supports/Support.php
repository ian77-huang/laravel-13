<?php

namespace App\Filament\Admin\Resources\Roles\Supports;

use App\Services\PermissionService;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class Support
{
    public static function getPermissions(): array
    {
        $permissions = config('permissions');

        return [$permissions['guards'], $permissions['modules'], $permissions['actions']];
    }

    public static function checkRoleData(array $data): array
    {
        $vaildData = [];
        if (! isset($data['name'])) {
            throw new Halt('name are required.');
        }
        $vaildData['name'] = $data['name'];
        if (! isset($data['permissions'])) {
            throw new Halt('Permissions are required.');
        }
        $vaildData['permissions'] = [];
        [$defaultGuards, $defaultModules, $defaultActions] = self::getPermissions();
        foreach ($data['permissions'] as $keyPermissions => $permissions) {
            if (! isset($defaultGuards[$keyPermissions])) {
                throw new Halt('Guards not found.');
            }
            $vaildData['permissions'][$keyPermissions] = [];
            foreach ($permissions as $keyPermission => $actions) {
                if (! isset($defaultModules[$keyPermission])) {
                    throw new Halt('Permission not found.');
                }
                $vaildData['permissions'][$keyPermissions][$keyPermission] = [];
                foreach ($actions as $keyAction => $action) {
                    if (! isset($defaultActions[$keyPermission][$action])) {
                        throw new Halt('Permission action not found.');
                    }
                    $vaildData['permissions'][$keyPermissions][$keyPermission][] = $action;
                }
            }
        }

        return $vaildData;
    }

    public static function formatPermissionsForForm(array $data, Model $record): array
    {
        $modulePermissions = app(PermissionService::class)
            ->formatPermissionsByModule($record->permissions);

        $data['permissions'] = [
            $record->guard_name => $modulePermissions,
        ];

        return $data;
    }

    public static function cleanupOrphanedPermissions(string $guardName): void
    {
        $usedByRoles = DB::table('role_has_permissions')
            ->pluck('permission_id');

        $usedByModels = DB::table('model_has_permissions')
            ->pluck('permission_id');

        $usedPermissionIds = $usedByRoles->merge($usedByModels)->unique();

        Permission::where('guard_name', $guardName)
            ->whereNotIn('id', $usedPermissionIds)
            ->delete();
    }
}

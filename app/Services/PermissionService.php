<?php

namespace App\Services;

use App\Models\User;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionService
{
    public function getRoles(): array
    {
        $roles = [];

        foreach (Role::all()->toArray() as $role) {
            $roles[$role['guard_name']][] = $role;
        }

        return $roles;
    }

    public function formatPermissionsByModule(Collection $permissions): array
    {
        $formatted = [];

        foreach (array_keys(config('permissions.modules', [])) as $module) {
            $formatted[$module] = $permissions
                ->filter(fn ($permission): bool => str_starts_with($permission->name, "{$module}."))
                ->map(fn ($permission): string => str_replace("{$module}.", '', $permission->name))
                ->values()
                ->all();
        }

        return $formatted;
    }

    public function getPermissions(): array
    {
        $permissions = config('permissions');

        return [$permissions['guards'], $permissions['modules'], $permissions['actions']];
    }

    public function validPermissions(array $dataPermissions): array
    {
        $validPermissions = [];
        [$defaultGuards, $defaultModules, $defaultActions] = $this->getPermissions();
        foreach ($dataPermissions as $keyPermissions => $permissions) {
            if (! isset($defaultGuards[$keyPermissions])) {
                throw new Halt('Guards not found.');
            }
            $validPermissions[$keyPermissions] = [];
            foreach ($permissions as $keyPermission => $actions) {
                if (! isset($defaultModules[$keyPermission])) {
                    throw new Halt('Permission not found.');
                }
                $validPermissions[$keyPermissions][$keyPermission] = [];
                foreach ((array) $actions as $keyAction => $action) {
                    if (! isset($defaultActions[$keyPermission][$action])) {
                        throw new Halt('Permission action not found.');
                    }
                    $validPermissions[$keyPermissions][$keyPermission][] = $action;
                }
            }
        }

        return $validPermissions;
    }

    public function validRoleData(array $data): array
    {
        $validData = [];
        if (! isset($data['name'])) {
            throw new Halt('name are required.');
        }
        $validData['name'] = $data['name'];
        if (! isset($data['permissions'])) {
            throw new Halt('Permissions are required.');
        }
        $validData['permissions'] = $this->validPermissions($data['permissions']);

        return $validData;
    }

    public function formatPermissionsForForm(array $data, Model $record): array
    {
        $modulePermissions = $this->formatPermissionsByModule($record->permissions);

        $data['permissions'] = [
            $record->guard_name => $modulePermissions,
        ];

        return $data;
    }

    public function cleanupOrphanedPermissions(string $guardName): void
    {
        $usedByRoles = Role::with('permissions:id')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('id');

        $usedByModels = User::with('permissions:id')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('id');

        $usedPermissionIds = $usedByRoles->merge($usedByModels)->unique();

        Permission::where('guard_name', $guardName)
            ->whereNotIn('id', $usedPermissionIds)
            ->delete();
    }

    public function formatPermissionsByGuard(): array
    {
        $configModules = config('permissions');

        $permissions = [];

        foreach ($configModules['guards'] as $module => $guard) {
            if (! isset($permissions[$guard])) {
                $permissions[$guard] = [];
                $permissions[$guard]['modules'] = [];
                $permissions[$guard]['actions'] = [];
            }
            if (! isset($configModules['modules'][$module])) {
                throw new Halt("can't fined permissions for ".$module.' setting.');
            }
            if (! isset($configModules['actions'][$module])) {
                throw new Halt("can't fined permissions actions for ".$module.' setting.');
            }
            $permissions[$guard]['modules'][$module] = $configModules['modules'][$module];
            $permissions[$guard]['actions'][$module] = $configModules['actions'][$module];
        }

        return $permissions;
    }
}

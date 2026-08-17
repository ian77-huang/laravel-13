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

    public function validRoles(array $data): array
    {
        $valid = [];
        $defaultRoles = $this->getRoles();

        foreach ($data as $guard => $roles) {
            if (! isset($valid[$guard])) {
                $valid[$guard] = [];
            }
            if (count($roles) === 0) {
                continue;
            }
            foreach ($roles as $role) {
                $exists = collect($defaultRoles[$guard])->contains('name', 'super_admin');
                if ($exists) {
                    $valid[$guard][] = $role;
                }
            }
        }

        return $valid;
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

    public function validUserData(array $data): array
    {
        $validData = [];

        if (! isset($data['roles'])) {
            throw new Halt('Permissions are required.');
        }
        $validData['roles'] = $this->validRoles($data['roles']);

        if (! isset($data['permissions'])) {
            throw new Halt('Permissions are required.');
        }
        $validData['permissions'] = $this->validPermissions($data['permissions']);

        echo '<pre>';
        var_dump(1, $validData);
        echo '</pre>';
        exit;

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
        [$configGuards, $configModules, $configActions] = $this->getPermissions();

        $permissions = [];

        foreach ($configGuards as $guard => $modules) {
            if (count($modules) === 0) {
                continue;
            }
            if (! isset($permission[$guard])) {
                $permission[$guard] = [];
                $permissions[$guard]['modules'] = [];
                $permissions[$guard]['actions'] = [];
            }
            foreach ($modules as $module) {
                if (! isset($configModules[$module])) {
                    throw new Halt("can't fined permissions for ".$module.' setting.');
                }
                if (! isset($configActions[$module])) {

                    throw new Halt("can't fined permissions actions for ".$module.' setting.');
                }

                $permissions[$guard]['modules'][$module] = $configModules[$module];
                $permissions[$guard]['actions'][$module] = $configActions[$module];
            }
        }

        return $permissions;
    }
}

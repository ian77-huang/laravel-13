<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class PermissionService
{
    public function getRoles(): array
    {
        $roles = [];

        foreach (Role::all()->toArray() as $role) {
            if (! isset($role['guard_name'])) {
                $roles[$role['guard_name']] = [];
            }
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
                dd("can't fined permissions for ".$module.' setting.', $module, $configModules);
            }
            if (! isset($configModules['actions'][$module])) {
                dd("can't fined permissions actions for ".$module.' setting.', $module, $configModules);
            }
            $permissions[$guard]['modules'][$module] = $configModules['modules'][$module];
            $permissions[$guard]['actions'][$module] = $configModules['actions'][$module];
        }

        return $permissions;
    }
}

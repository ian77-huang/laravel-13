<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PermissionService
{
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
}

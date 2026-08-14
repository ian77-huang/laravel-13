<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PermissionService
{
    /**
     * 將使用者的權限集合依據 config 模組分類並去除前綴
     */
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

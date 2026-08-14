<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Permission;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

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
        foreach (array_keys(config('permissions.modules')) as $module) {
            $data[$module] = $this->record->permissions
                ->filter(fn ($permission): bool => str_starts_with($permission->name, "{$module}."))
                ->map(fn ($permission): string => str_replace("{$module}.", '', $permission->name))
                ->values()
                ->all();
        }

        return $data;
    }
}

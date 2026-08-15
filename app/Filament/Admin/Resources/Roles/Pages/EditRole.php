<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Components\Actions\DeleteAction;
use App\Filament\Components\Actions\ViewAction;
use App\Filament\Components\Records\EditRecord;
use App\Services\PermissionService;
use Spatie\Permission\Models\Permission;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label(__('button.view')),
            DeleteAction::make()
                ->label(__('button.delete')),
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
        $modulePermissions = app(PermissionService::class)
            ->formatPermissionsByModule($this->record->permissions);

        return array_merge($data, $modulePermissions);
    }
}

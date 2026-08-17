<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Admin\Resources\Roles\Supports\Support;
use App\Filament\Custom\Actions\ViewAction;
use App\Filament\Custom\Records\EditRecord;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make()
                ->label(__('button.view')),
            Action::make('delete')
                ->label(__('button.delete'))
                ->requiresConfirmation()
                ->action(function (): void {
                    $guardName = $this->record->guard_name;

                    DB::transaction(function () use ($guardName) {
                        $this->record->delete();
                        Support::cleanupOrphanedPermissions($guardName);
                    });

                    app(PermissionRegistrar::class)->forgetCachedPermissions();

                    $this->redirect(route('filament.admin.resources.roles.index'));
                }),
        ];
    }

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

            Support::cleanupOrphanedPermissions($guardName);

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

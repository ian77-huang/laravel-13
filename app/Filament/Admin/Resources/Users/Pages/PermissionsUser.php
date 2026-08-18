<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Custom\Components\CheckboxList;
use App\Filament\Custom\Records\EditBaseRecord;
use App\Filament\Custom\Traits\HasEditRecord;
use App\Services\PermissionService;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Enums\GridDirection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// use Spatie\Permission\Traits\HasRoles;
// use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class PermissionsUser extends EditBaseRecord
{
    use HasEditRecord;

    protected static string $resource = UserResource::class;

    // protected string $view = 'filament.admin.resources.users.pages.permissions-user';

    protected static array $transKeys = [
        'breadcrumbs' => ['front' => 'user.user', 'back' => 'permission.title'],
        'main' => 'permission.title',
    ];

    public function form(Schema $schema): Schema
    {
        $premissionSevice = app(PermissionService::class);

        $sectionRoles = [];
        $sectionPermissions = [];

        foreach ($premissionSevice->getRoles() as $keyRole => $roles) {
            $checkboxRoles = CheckboxList::make($keyRole)
                ->label('')
                ->options(
                    fn (): array => collect($roles)
                        ->mapWithKeys(
                            fn (array $role): array => [$role['name'] => $role['name']],
                        )
                        ->toArray(),
                )
                ->columns(4)
                ->bulkToggleable()
                ->gridDirection(GridDirection::Row);
            $sectionRoles[] = Section::make(__('permission.guard').' => '.__('permission.guards.'.$keyRole))
                ->columns(1)
                ->columnSpanFull()
                ->schema([$checkboxRoles]);
        }
        foreach ($premissionSevice->formatPermissionsByGuard() as $guardKey => $guards) {
            $checkboxLists = [];
            foreach ($guards['modules'] as $keyModule => $valModule) {
                $checkboxLists[] = CheckboxList::make($keyModule)
                    ->label(__($valModule))
                    ->options(
                        fn (): array => collect(config('permissions.actions')[$keyModule])
                            ->mapWithKeys(
                                fn (array $action): array => [$action['key'] => __($action['value'])],
                            )
                            ->toArray(),
                    )
                    ->columns(4)
                    ->columnSpanFull()
                    ->bulkToggleable()
                    ->gridDirection(GridDirection::Row);
                $checkboxLists[] = View::make('filament.components.divider')
                    ->columnSpanFull();
            }
            array_pop($checkboxLists);
            $sectionPermissions[] = Section::make(__('permission.guard').' => '.__('permission.guards.'.$guardKey))
                ->statePath($guardKey)
                ->columns(2)
                ->columnSpanFull()
                ->schema([...$checkboxLists]);
        }

        return $schema
            ->components([
                Fieldset::make('roles')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        Group::make()
                            ->statePath('roles')
                            ->schema([
                                ...$sectionRoles,
                            ])
                            ->columnSpanFull(),
                    ]),
                View::make('filament.components.divider')
                    ->columnSpanFull(),
                Fieldset::make('permissions')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        Group::make()
                            ->statePath('permissions')
                            ->schema([
                                ...$sectionPermissions,
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $permissionService = app(PermissionService::class);

        $data = $permissionService->formatPermissionsForForm($data, $this->record);

        $rolesByGuard = [];
        foreach ($this->record->roles as $role) {
            $rolesByGuard[$role->guard_name][] = $role->name;
        }
        $data['roles'] = $rolesByGuard;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return app(PermissionService::class)->validUserData($data);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $roles = [];
        if (isset($data['roles'])) {
            foreach ($data['roles'] as $guard => $guardRoles) {
                if (! is_array($guardRoles)) {
                    continue;
                }
                foreach ($guardRoles as $roleName) {
                    $role = Role::findByName($roleName, $guard);
                    if ($role) {
                        $roles[] = $role;
                    }
                }
            }
        }
        $record->syncRoles($roles);

        $permissions = [];
        if (isset($data['permissions'])) {
            foreach ($data['permissions'] as $guard => $modules) {
                if (! is_array($modules)) {
                    continue;
                }
                foreach ($modules as $module => $actions) {
                    if (! is_array($actions)) {
                        continue;
                    }
                    foreach ($actions as $action) {
                        $permission = Permission::findByName("{$action}:{$module}", $guard);
                        if ($permission) {
                            $permissions[] = $permission;
                        }
                    }
                }
            }
        }
        $record->syncPermissions($permissions);

        unset($data['roles'], $data['permissions']);

        $record->fill($data);
        $record->save();

        return $record;
    }
}

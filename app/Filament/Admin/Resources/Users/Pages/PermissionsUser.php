<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Custom\Components\CheckboxList;
use App\Filament\Custom\Records\EditBaseRecord;
use App\Services\PermissionService;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\GridDirection;

class PermissionsUser extends EditBaseRecord
{
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
            $sectionRoles[] = Section::make('Section.'.$keyRole)
                ->statePath('roles')
                ->columns(1)
                ->columnSpanFull()
                ->schema([$checkboxRoles]);
        }

        // foreach ($premissionSevice->formatPermissionsByGuard() as $guardKey => $guards) {

        //     $checkboxLists = [];
        //     foreach ($guards['modules'] as $keyModule => $valModule) {
        //         $checkboxLists[] = CheckboxList::make($keyModule)
        //             ->label(__($valModule))
        //             ->options(
        //                 fn (): array => collect(config('permissions.actions')[$keyModule])
        //                     ->mapWithKeys(
        //                         fn (array $action): array => [$action['key'] => __($action['value'])],
        //                     )
        //                     ->toArray(),
        //             )
        //             ->columns(4)
        //             ->bulkToggleable()
        //             ->gridDirection(GridDirection::Row);
        //     }

        //     $sectionPermissions[] = Section::make($guardKey)
        //         ->statePath($guardKey)
        //         ->columns(2)
        //         ->columnSpanFull()
        //         ->schema([...$checkboxLists]);

        // }

        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Roles')
                            ->schema([
                                ...$sectionRoles,
                            ]),
                        Tab::make('Permissions')
                            ->schema([
                                Group::make()
                                    ->statePath('permissions')
                                    ->components($sectionPermissions),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $modulePermissions = app(PermissionService::class)
            ->formatPermissionsByModule($this->record->permissions);

        return array_merge($data, $modulePermissions);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {

        if (isset($data['roles'])) {
            foreach ($data['roles'] as $keyRoles => $role) {

            }

        }
        $configPermissions = config('permissions');
        $guards = array_values($configPermissions['guards']);
        echo '<pre>';
        var_dump($guards, $data);
        echo '</pre>';
        exit;
        // $guardName = config('permission.defaults.guard', 'web');

        // $permissions = [];
        // foreach ($data as $module => $actions) {
        //     if (! is_array($actions)) {
        //         continue;
        //     }

        //     foreach ($actions as $action) {
        //         $permissionName = "{$module}.{$action}";

        //         Permission::firstOrCreate([
        //             'name' => $permissionName,
        //             'guard_name' => $guardName,
        //         ]);

        //         $permissions[] = $permissionName;
        //     }
        // }

        // $this->record->syncPermissions($permissions);

        // foreach (array_keys(config('permissions.modules')) as $module) {
        //     unset($data[$module]);
        // }

        return $data;
    }
}

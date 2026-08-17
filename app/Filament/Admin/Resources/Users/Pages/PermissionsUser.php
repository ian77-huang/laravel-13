<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Custom\Components\CheckboxList;
use App\Filament\Custom\Records\EditBaseRecord;
use App\Services\PermissionService;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Enums\GridDirection;
use Illuminate\Database\Eloquent\Model;

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
                    ->bulkToggleable()
                    ->gridDirection(GridDirection::Row);
            }
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
        $modulePermissions = app(PermissionService::class)
            ->formatPermissionsByModule($this->record->permissions);

        return array_merge($data, $modulePermissions);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return app(PermissionService::class)->validUserData($data);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return $record;
    }
}

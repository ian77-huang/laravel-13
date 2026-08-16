<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;

class PermissionsUser1 extends Page
{
    protected static string $resource = UserResource::class;

    // protected string $view = 'filament.admin.resources.users.pages.permissions-user';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('site_name')->required(),
            ])
            ->statePath('data');
    }

    protected function getViewData(): array
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

        return ['permissions' => $permissions];
    }
}

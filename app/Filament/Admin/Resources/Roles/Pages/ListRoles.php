<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Components\Records\ListRecords;
use Filament\Actions\CreateAction;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    public function getCustomBreadcrumb(): ?string
    {
        return __('role.role');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('button.create')),
        ];
    }
}

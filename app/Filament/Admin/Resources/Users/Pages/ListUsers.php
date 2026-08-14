<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getBreadcrumb(): ?string
    {
        return __('user.user');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('button.create')),
        ];
    }
}

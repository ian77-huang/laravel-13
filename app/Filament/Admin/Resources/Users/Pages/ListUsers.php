<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Custom\Actions\Action;
use App\Filament\Custom\Actions\CreateAction;
use App\Filament\Custom\Records\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected static array $transKeys = ['breadcrumb' => 'user.user'];

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label(__('button.create')),
            Action::make('button.user.broadcastAll')
                ->color('info')
                ->label(__('button.user.broadcastAll'))
                ->setPermission('View:Broadcast')
                ->icon('heroicon-o-megaphone')
                ->url(fn ($record): string => route('filament.admin.resources.users.broadcastAll', [])),
        ];
    }
}

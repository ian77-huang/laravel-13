<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Custom\Records\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected static array $transKeys = [
        'breadcrumbs' => ['front' => 'user.user', 'back' => 'button.edit'],
    ];
}

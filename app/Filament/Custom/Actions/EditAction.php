<?php

namespace App\Filament\Custom\Actions;

use Filament\Actions\EditAction as FilamentEditAction;

class EditAction extends FilamentEditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('button.edit'));
    }
}

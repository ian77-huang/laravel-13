<?php

namespace App\Filament\Custom\Components;

use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList as FilamentCheckboxList;

class CheckboxList extends FilamentCheckboxList
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->selectAllAction(fn (Action $action) => $action->label(__('button.select_all')))
            ->deselectAllAction(fn (Action $action) => $action->label(__('button.deselect_all')));
    }
}

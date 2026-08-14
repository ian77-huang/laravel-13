<?php

namespace App\Filament\Components;

use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList as FilamentCheckboxList;

class CheckboxList extends FilamentCheckboxList
{
    protected function setUp(): void
    {
        parent::setUp();

        // 統一設定全選與取消全選的按鈕語系
        $this
            ->selectAllAction(fn (Action $action) => $action->label(__('button.select_all')))
            ->deselectAllAction(fn (Action $action) => $action->label(__('button.deselect_all')));
    }
}

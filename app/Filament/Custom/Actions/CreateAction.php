<?php

namespace App\Filament\Custom\Actions;

use Filament\Actions\CreateAction as FilamentCreateAction;

class CreateAction extends FilamentCreateAction
{
    protected function setUp(): void
    {
        parent::setUp();

        // 統一設定全選與取消全選的按鈕語系
        $this->label(__('button.create'));
    }
}

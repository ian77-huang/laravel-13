<?php

namespace App\Filament\Components\Actions;

use Filament\Actions\ViewAction as FilamentViewAction;

class ViewAction extends FilamentViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        // 統一設定全選與取消全選的按鈕語系
        $this->label(__('button.view'));
    }
}

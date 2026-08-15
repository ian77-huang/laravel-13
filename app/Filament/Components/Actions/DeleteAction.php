<?php

namespace App\Filament\Components\Actions;

use Filament\Actions\DeleteAction as FilamentDeleteAction;

class DeleteAction extends FilamentDeleteAction
{
    protected function setUp(): void
    {
        parent::setUp();

        // 統一設定全選與取消全選的按鈕語系
        $this->label(__('button.delete'));
    }
}

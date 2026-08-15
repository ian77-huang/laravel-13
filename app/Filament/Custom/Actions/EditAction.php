<?php

namespace App\Filament\Custom\Actions;

use Filament\Actions\EditAction as FilamentEditAction;

class EditAction extends FilamentEditAction
{
    protected function setUp(): void
    {
        parent::setUp();

        // 統一設定全選與取消全選的按鈕語系
        $this->label(__('button.edit'));
    }
}

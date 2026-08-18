<?php

namespace App\Filament\Custom\Records;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord as FilamentCreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateRecord extends FilamentCreateRecord
{
    /**
     * List 頁面的 breadcrumb 翻譯 key。
     *
     * @var array{breadcrumb: string}
     */
    protected static array $transKeys = [
        'breadcrumbs' => ['front' => null, 'back' => null],
    ];

    public function getBreadcrumbs(): array
    {
        return [__(static::$transKeys['breadcrumbs']['front'] ?? 'transKeys.breadcrumbs.front'), __(static::$transKeys['breadcrumbs']['back'] ?? 'transKeys.breadcrumbs.back')];
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament.title.page.create', ['label' => __(static::$transKeys['breadcrumbs']['front'] ?? 'transKeys.main')]);
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()->label(__('button.create'));
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()->label(__('button.create_and_create_another'));
    }
}

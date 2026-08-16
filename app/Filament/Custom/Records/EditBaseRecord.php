<?php

namespace App\Filament\Custom\Records;

use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord as FilamentEditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditBaseRecord extends FilamentEditRecord
{
    /**
     * List 頁面的 breadcrumb 翻譯 key。
     *
     * @var array{breadcrumb: string}
     */
    protected static array $transKeys = [
        'breadcrumbs' => ['front' => null, 'back' => null],
        'main' => null,
    ];

    public function getBreadcrumbs(): array
    {
        return [__(static::$transKeys['breadcrumbs']['front'] ?? 'transKeys.breadcrumbs.front'), __(static::$transKeys['breadcrumbs']['back'] ?? 'transKeys.breadcrumbs.back')];
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament.title.page.edit', ['label' => __(static::$transKeys['main'] ?? 'transKeys.main')]);

        return '12345';
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()->label(__('button.save'));
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()->label(__('button.cancel'));
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}

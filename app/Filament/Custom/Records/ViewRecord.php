<?php

namespace App\Filament\Custom\Records;

use App\Filament\Custom\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord as FilamentViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewRecord extends FilamentViewRecord
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
        return __('filament.title.page.view', ['label' => __(static::$transKeys['breadcrumbs']['front'] ?? 'transKeys.main')]);
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label(__('button.edit')),
        ];
    }
}

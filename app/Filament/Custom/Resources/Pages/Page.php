<?php

namespace App\Filament\Custom\Resources\Pages;

use Filament\Resources\Pages\Page as FilamentResourcesPage;
use Illuminate\Contracts\Support\Htmlable;

class Page extends FilamentResourcesPage
{
    /**
     * List 頁面的 breadcrumb 翻譯 key。
     *
     * @var array{breadcrumb: string}
     */
    protected static array $transKeys = [
        'breadcrumbs' => ['front' => null, 'back' => null],
        'button' => ['submit' => null, 'cancel' => null],
    ];

    public function getBreadcrumbs(): array
    {
        return [__(static::$transKeys['breadcrumbs']['front'] ?? 'transKeys.breadcrumbs.front'), __(static::$transKeys['breadcrumbs']['back'] ?? 'transKeys.breadcrumbs.back')];
    }

    public function getTitle(): string|Htmlable
    {
        return __('filament.title.page.create', ['label' => __(static::$transKeys['breadcrumbs']['front'] ?? 'transKeys.main')]);
    }

    public function mount(): void
    {
        $this->form->fill([]);
    }
}

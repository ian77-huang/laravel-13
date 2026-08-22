<?php

namespace App\Filament\Custom\Resources\Pages;

use Filament\Resources\Pages\Page as FilamentResourcesPage;
use Illuminate\Contracts\Support\Htmlable;

class Page extends FilamentResourcesPage
{
    protected string $view = 'filament.admin.resources.pages.page';

    public ?string $previousUrl = null;

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

    public function getTitleButtonSubmit(): string|Htmlable
    {
        return __(static::$transKeys['button']['submit']);
    }

    public function getTitleButtonCancel(): string|Htmlable
    {
        return __(static::$transKeys['button']['cancel'] ?? 'button.cancel');
    }

    public function mount(): void
    {
        $this->form->fill([]);

        $this->previousUrl = url()->previous();
    }

    public function cancel(): void
    {
        $this->redirect(
            $this->previousUrl
        );
    }
}

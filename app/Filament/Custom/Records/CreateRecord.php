<?php

namespace App\Filament\Custom\Records;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord as FilamentCreateRecord;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Js;

class CreateRecord extends FilamentCreateRecord
{
    protected static bool $canCreateAnother = true;

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

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()->label(__('button.create'));
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()->label(__('button.create_and_create_another'));
    }

    protected function getFormActions(): array
    {
        return [
            $this->getFormCreateAction(),
            ...($this->canCreateAnother() ? [$this->getFormCreateAnotherAction()] : []),
            $this->getFormCancelAction(),
        ];
    }

    protected function getFormCreateAction(): Action
    {
        $hasFormWrapper = $this->hasFormWrapper();

        $label = 'filament-panels::resources/pages/create-record.form.actions.create.label';
        if (isset(static::$transKeys['button']['submit']) && is_string(static::$transKeys['button']['submit'])) {
            $label = static::$transKeys['button']['submit'];
        }

        return Action::make('create')
            ->label(__($label))
            ->submit($hasFormWrapper ? $this->getSubmitFormLivewireMethodName() : null)
            ->action($hasFormWrapper ? null : $this->getSubmitFormLivewireMethodName())
            ->keyBindings(['mod+s']);
    }

    protected function getFormCancelAction(): Action
    {
        $url = $this->previousUrl ?? $this->getResourceUrl();

        $label = 'filament-panels::resources/pages/create-record.form.actions.cancel.label';
        if (isset(static::$transKeys['button']['cancel']) && is_string(static::$transKeys['button']['cancel'])) {
            $label = static::$transKeys['button']['cancel'];
        }

        return Action::make('cancel')
            ->label(__($label))
            ->alpineClickHandler(
                FilamentView::hasSpaMode($url)
                    ? 'document.referrer ? window.history.back() : Livewire.navigate('.Js::from($url).')'
                    : 'document.referrer ? window.history.back() : (window.location.href = '.Js::from($url).')',
            )
            ->color('gray');
    }

    protected function getFormCreateAnotherAction(): Action
    {
        return Action::make('createAnother')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.create_another.label'))
            ->action('createAnother')
            ->keyBindings(['mod+shift+s'])
            ->color('gray');
    }
}

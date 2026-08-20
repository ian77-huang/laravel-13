<?php

namespace App\Filament\Custom\Records;

use App\Filament\Custom\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords as FilamentListRecords;
use Illuminate\Support\Facades\Auth;

abstract class ListRecords extends FilamentListRecords
{
    /**
     * List 頁面的 breadcrumb 翻譯 key。
     *
     * @var array{breadcrumb: string}
     */
    protected static array $transKeys = ['breadcrumb' => null];

    public function getBreadcrumb(): ?string
    {
        return __(static::$transKeys['breadcrumb'] ?? 'Missing Group');
    }

    protected function getHeaderActions(): array
    {
        echo '<pre>';
        var_dump(Auth::user()->id);
        echo '</pre>';
        // exit;

        return [
            CreateAction::make()
                ->label(__('button.create')),
        ];
    }
}

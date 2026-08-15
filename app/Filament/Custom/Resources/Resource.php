<?php

namespace App\Filament\Custom\Resources;

use Filament\Resources\Resource as FilamentResource;
use UnitEnum;

class Resource extends FilamentResource
{
    /**
     * Main page transtion key。
     *
     * @var array{model: string, models: string, navGroup: string}
     */
    protected static array $transKeys = [
        'group' => null,
        'main' => null,
    ];

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __(static::$transKeys['group'] ?? 'Missing Group');
    }

    public static function getNavigationLabel(): string
    {
        return __(static::$transKeys['main'] ?? 'Missing Main');
    }

    public static function getBreadcrumb(): string
    {
        return __(static::$transKeys['group'] ?? 'Missing Main');
    }

    public static function getModelLabel(): string
    {
        return __(static::$transKeys['main'] ?? 'Missing Main');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.title.page.list', ['label' => __(static::$transKeys['main'] ?? 'Missing Main')]);
    }
}

<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\GridDirection;
use Illuminate\Validation\Rules\Unique;

class RoleForm
{
    private static function createCheckbox(?string $name = null): CheckboxList
    {
        return CheckboxList::make($name)
            ->selectAllAction(fn (Action $action) => $action->label(__('button.select_all')))
            ->deselectAllAction(fn (Action $action) => $action->label(__('button.deselect_all')));
    }

    public static function configure(Schema $schema): Schema
    {
        $checkboxList = [];

        $modules = config('permissions.modules');
        $actions = config('permissions.actions');

        foreach ($modules as $moduleKey => $module) {
            array_push($checkboxList, self::createCheckbox($moduleKey)
                ->label(__($module))
                ->options(
                    fn (): array => collect($actions[$moduleKey])
                        ->mapWithKeys(
                            fn (array $action): array => [$action['key'] => __($action['value'])],
                        )
                        ->toArray(),
                )
                ->columns(4)
                ->bulkToggleable()
                ->gridDirection(GridDirection::Row));
        }

        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('role.name'))
                    ->required()
                    ->unique(
                        table: config('permission.table_names.roles'),
                        column: 'name',
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule) => $rule->where(
                            'guard_name',
                            request()->input('data.guard_name', 'web'),
                        ),
                    ),
                Select::make('guard_name')
                    ->label(__('permission.navigation.permission'))
                    ->options(fn (): array => array_combine(
                        array_keys(config('auth.guards')),
                        array_keys(config('auth.guards')),
                    ))
                    ->default('web')
                    ->required(),
                Section::make(__('permission.navigation.permission'))
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([...$checkboxList]),
            ]);
    }
}

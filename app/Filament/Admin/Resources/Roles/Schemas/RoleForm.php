<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use App\Filament\Custom\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\GridDirection;
use Illuminate\Validation\Rules\Unique;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        $guards = config('permissions.guards');
        $modules = config('permissions.modules');
        $actions = config('permissions.actions');

        $sections = [];

        foreach ($guards as $keyGuards => $guardModules) {
            if (count($guardModules) === 0) {
                continue;
            }

            $checkboxList = [];
            foreach ($guardModules as $module) {
                if (! isset($modules[$module])) {
                    continue;
                }

                $checkboxList[] = CheckboxList::make($module)
                    ->label(__($modules[$module]))
                    ->options(
                        fn (): array => collect($actions[$module])
                            ->mapWithKeys(
                                fn (array $action): array => [$action['key'] => __($action['value'])],
                            )
                            ->toArray(),
                    )
                    ->columns(4)
                    ->bulkToggleable()
                    ->gridDirection(GridDirection::Row);
            }
            $sections[] = Section::make(__('permission.guard').' => '.__('permission.guards.'.$keyGuards))
                ->statePath($keyGuards)
                ->columns(2)
                ->columnSpanFull()
                ->schema([...$checkboxList]);
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
                    )
                    ->columnSpanFull(),
                Group::make()
                    ->statePath('permissions')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema($sections),
            ]);
    }
}

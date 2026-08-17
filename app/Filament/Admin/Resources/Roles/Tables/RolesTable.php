<?php

namespace App\Filament\Admin\Resources\Roles\Tables;

use App\Filament\Admin\Resources\Roles\Supports\Support;
use App\Filament\Custom\Actions\EditAction;
use App\Filament\Custom\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('role.id'))
                    ->sortable(),
                TextColumn::make('name')
                    ->label(__('role.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guard_name')
                    ->label(__('role.guard_name')),
                TextColumn::make('created_at')
                    ->label(__('role.created_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('button.view')),
                EditAction::make()
                    ->label(__('button.edit')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('button.delete'))
                        ->action(function ($records): void {
                            $guardNames = $records->pluck('guard_name')->unique()->values()->all();
                            $roleIds = $records->pluck('id')->all();

                            DB::transaction(function () use ($roleIds, $guardNames) {
                                DB::table('roles')->whereIn('id', $roleIds)->delete();

                                foreach ($guardNames as $guardName) {
                                    Support::cleanupOrphanedPermissions($guardName);
                                }
                            });

                            app(PermissionRegistrar::class)->forgetCachedPermissions();
                        }),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Resources\Roles\Tables;

use App\Filament\Custom\Actions\EditAction;
use App\Filament\Custom\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                        ->label(__('button.delete')),
                ]),
            ]);
    }
}

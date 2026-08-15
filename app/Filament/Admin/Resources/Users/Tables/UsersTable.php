<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use App\Filament\Custom\Actions\EditAction;
use App\Filament\Custom\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('user.auth.name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('user.auth.email'))
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label(__('user.auth.email_verified_at'))
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('user.auth.is_active'))
                    ->boolean(),
                IconColumn::make('is_admin')
                    ->label(__('user.auth.is_admin'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('user.auth.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('user.auth.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

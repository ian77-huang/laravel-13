<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label(__('user.auth.name')),
                TextEntry::make('email')
                    ->label(__('user.auth.email')),
                TextEntry::make('email_verified_at')
                    ->label(__('user.auth.email_verified_at'))
                    ->dateTime()
                    ->placeholder('-'),
                // TextEntry::make('two_factor_secret')
                //     ->label(__('user.auth.two_factor_secret'))
                //     ->placeholder('-')
                //     ->columnSpanFull(),
                // TextEntry::make('two_factor_recovery_codes')
                //     ->label(__('user.auth.two_factor_recovery_codes'))
                //     ->placeholder('-')
                //     ->columnSpanFull(),
                // TextEntry::make('two_factor_confirmed_at')
                //     ->label(__('user.auth.two_factor_confirmed_at'))
                //     ->dateTime()
                //     ->placeholder('-'),
                IconEntry::make('is_active')
                    ->label(__('user.auth.is_active'))
                    ->boolean(),
                IconEntry::make('is_admin')
                    ->label(__('user.auth.is_admin'))
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label(__('user.auth.created_at'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('user.auth.updated_at'))
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        $isCreate = $schema->getOperation() === 'create';

        $components = [
            'name' => TextInput::make('name')
                ->label(__('user.auth.name'))
                ->required()
                ->autocomplete('name'),
            'email' => TextInput::make('email')
                ->label(__('user.auth.email'))
                ->email()
                ->required()
                ->autocomplete('email'),
            'email_verified_at' => DateTimePicker::make('email_verified_at')
                ->label(__('user.auth.email_verified_at')),
            'password' => TextInput::make('password')
                ->label(__('user.auth.password'))
                ->password()
                ->required()
                ->autocomplete('new-password'),
            Section::make('')
                ->columns(2)
                ->columnSpanFull()
                ->schema([
                    Toggle::make('is_active')
                        ->label(__('user.auth.is_active'))
                        ->required(),
                    Toggle::make('is_admin')
                        ->label(__('user.auth.is_admin'))
                        ->required(),
                ]),
        ];
        if (! $isCreate) {
            unset($components['password']);
        }

        return $schema
            ->components($components);
    }
}

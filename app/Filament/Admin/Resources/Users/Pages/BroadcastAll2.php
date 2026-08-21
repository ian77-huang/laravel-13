<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Custom\Records\Record;
use App\Filament\Custom\Traits\HasEditRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Broadcast;
use Spatie\Permission\Models\Role;

class BroadcastAll2 extends Record
{
    // use HasEditRecord;

    protected static string $resource = UserResource::class;

    // public function __construct()
    // {
    //     throw new \Exception('Not implemented');
    // }

    // protected static array $transKeys = [
    //     'breadcrumbs' => ['front' => 'user.user', 'back' => 'broadcast.title'],
    //     'main' => 'broadcast.title',
    // ];

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label(__('broadcast.form.title'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Select::make('type')
                    ->label(__('broadcast.form.type'))
                    ->options([
                        'info' => 'Info',
                        'success' => 'Success',
                        'warning' => 'Warning',
                        'error' => 'Error',
                    ])
                    ->default('info')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('message')
                    ->label(__('broadcast.form.message'))
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
                Select::make('target')
                    ->label(__('broadcast.form.target'))
                    ->options([
                        'all' => __('broadcast.target.all'),
                        'role' => __('broadcast.target.role'),
                    ])
                    ->default('all')
                    ->required()
                    ->reactive()
                    ->columnSpanFull(),
                Select::make('roles')
                    ->label(__('broadcast.form.roles'))
                    ->options(fn () => Role::pluck('name', 'name')->toArray())
                    ->multiple()
                    ->visible(fn ($get) => $get('target') === 'role')
                    ->columnSpanFull(),
            ]);
    }

    // public function save(): void
    // {
    //     $data = $this->form->getState();

    //     $payload = [
    //         'title' => $data['title'],
    //         'message' => $data['message'],
    //         'type' => $data['type'],
    //     ];

    //     if ($data['target'] === 'all') {
    //         Broadcast::channel('broadcast.all', true);
    //         broadcast()->to('broadcast.all')->event('broadcast.message', $payload);
    //     } else {
    //         foreach ($data['roles'] as $role) {
    //             broadcast()->to("broadcast.role.{$role}")->event('broadcast.message', $payload);
    //         }
    //     }

    //     Notification::make()
    //         ->success()
    //         ->title(__('broadcast.notification.sent'))
    //         ->send();

    //     $this->form->fill();
    // }
}

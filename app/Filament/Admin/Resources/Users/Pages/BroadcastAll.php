<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Custom\Resources\Pages\Page;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Broadcast;

class BroadcastAll extends Page
{
    protected static string $resource = UserResource::class;

    protected string $view = 'filament.admin.resources.users.pages.broadcastAll';

    public ?array $data = [];

    protected static array $transKeys = [
        'breadcrumbs' => ['front' => 'user.user', 'back' => 'broadcast.title'],
        'button' => ['submit' => 'button.dispatch', 'cancel' => 'button.cancel'],
    ];

    public function getTitle(): string|Htmlable
    {
        return __('broadcast.title');
    }

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
                        'user' => __('broadcast.target.user'),
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
                Select::make('user_id')
                    ->label(__('user.user'))
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search): array {
                        return User::query()
                            ->where('name', 'like', "%{$search}%")
                            ->limit(20)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->multiple()
                    ->visible(fn ($get) => $get('target') === 'user')
                    ->getOptionLabelsUsing(
                        fn (array $values): array => User::query()
                            ->whereIn('id', $values)
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->searchDebounce(300),
            ])->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $payload = [
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'],
        ];

        if ($data['target'] === 'all') {
            Broadcast::on('broadcast.all')
                ->as('broadcast.message')
                ->with($payload)
                ->send();
        } else {
            foreach ($data['roles'] as $role) {
                Broadcast::on("broadcast.role.{$role}")
                    ->as('broadcast.message')
                    ->with($payload)
                    ->send();
            }
        }

        Notification::make()
            ->success()
            ->title(__('broadcast.notification.sent'))
            ->send();

        // dd($data);
    }
}

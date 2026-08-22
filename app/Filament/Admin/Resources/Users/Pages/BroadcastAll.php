<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Custom\Resources\Pages\Page;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Broadcast;
use Spatie\Permission\Models\Role;

class BroadcastAll extends Page
{
    protected static string $resource = UserResource::class;

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
                Select::make('userIds')
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
        if (isset($data['roles'])) {
            $roles = Role::whereIn('name', $data['roles'])
                ->pluck('name')
                ->toArray();
            if (count($data['roles']) !== count($roles)) {
                throw new Halt('Roles is erros.');
            }
        }
        if (isset($data['userIds'])) {
            $userIds = User::whereIn('id', $data['userIds'])
                ->pluck('id')
                ->toArray();
            if (count($data['userIds']) !== count($userIds)) {
                throw new Halt('userIds is erros.');
            }
        }

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
        }

        $userIds = [];
        if ($data['target'] === 'role') {
            foreach ($data['roles'] as $role) {
                $users = User::role($role)->get();
                foreach ($users as $user) {
                    array_push($userIds, $user->id);
                }
            }
        }
        if ($data['target'] === 'user') {
            foreach ($data['userIds'] as $userId) {
                array_push($userIds, $userId);
            }
        }

        if (count($userIds) !== 0) {
            foreach ($userIds as $userId) {
                Broadcast::on("broadcast.user.{$userId}")
                    ->as('broadcast.message')
                    ->with($payload)
                    ->send();
            }
        }

        $titleNotification = __('broadcast.notification.sent.success');
        if ($data['target'] === 'all') {
            $titleNotification .= '('.__('broadcast.target.all').')';
        }
        if ($data['target'] === 'role') {
            $titleNotification .= '('.__('broadcast.target.role').')';
        }
        if ($data['target'] === 'user') {
            $titleNotification .= '('.__('broadcast.target.user').')';
        }

        Notification::make()
            ->success()
            ->title($titleNotification)
            ->send();

        $this->form->fill();
    }
}

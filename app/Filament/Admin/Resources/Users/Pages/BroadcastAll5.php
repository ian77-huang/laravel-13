<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Filament\Custom\Records\CreateRecord;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class BroadcastAll5 extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static array $transKeys = [
        'breadcrumbs' => ['front' => 'user.user', 'back' => 'broadcast.title'],
        'button' => ['submit' => 'button.dispatch', 'cancel' => 'button.dispatch'],
    ];

    public function getTitle(): string|Htmlable
    {
        return __('broadcast.title');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
    }

    protected function handleRecordCreation(array $data): Model
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        exit;

        return parent::handleRecordCreation($data);
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
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search): array {
                        return User::query()
                            ->where('name', 'like', "%{$search}%")
                            ->limit(20)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->multiple()
                    ->getOptionLabelUsing(
                        fn ($value): ?string => User::find($value)?->name
                    )
                    ->searchDebounce(300),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        echo '<pre>';
        var_dump(1);
        echo '</pre>';

        return $data;
    }
}

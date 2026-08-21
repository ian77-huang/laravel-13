<?php

// namespace App\Filament\Admin\Resources\Users\Pages;

// use App\Filament\Admin\Resources\Users\UserResource;
// use Filament\Forms\Components\Select;
// use Filament\Forms\Components\Textarea;
// use Filament\Forms\Components\TextInput;
// use Filament\Resources\Pages\Concerns\InteractsWithRecord;
// use Filament\Resources\Pages\Page;
// use Filament\Schemas\Schema;

// class BroadcastAll3 extends Page
// {
//     use InteractsWithRecord;

//     protected static string $resource = UserResource::class;

//     protected string $view = 'filament.admin.resources.users.pages.broadcast-all1';

//     public string $title = '';

//     public string $message = '';

//     public function mount(int|string $record): void
//     {
//         $this->record = $this->resolveRecord($record);
//     }
//     public function mount(): void
//     {
//         $this->form->fill();
//     }

//     public function form(Schema $schema): Schema
//     {
//         return $schema
//             ->components([
//                 TextInput::make('title')
//                     ->label(__('broadcast.form.title'))
//                     ->required()
//                     ->maxLength(255)
//                     ->columnSpanFull(),
//                 Select::make('type')
//                     ->label(__('broadcast.form.type'))
//                     ->options([
//                         'info' => 'Info',
//                         'success' => 'Success',
//                         'warning' => 'Warning',
//                         'error' => 'Error',
//                     ])
//                     ->default('info')
//                     ->required()
//                     ->columnSpanFull(),
//                 Textarea::make('message')
//                     ->label(__('broadcast.form.message'))
//                     ->required()
//                     ->rows(5)
//                     ->columnSpanFull(),
//                 Select::make('target')
//                     ->label(__('broadcast.form.target'))
//                     ->options([
//                         'all' => __('broadcast.target.all'),
//                         'role' => __('broadcast.target.role'),
//                     ])
//                     ->default('all')
//                     ->required()
//                     ->reactive()
//                     ->columnSpanFull(),
//                 // Select::make('roles')
//                 //     ->label(__('broadcast.form.roles'))
//                 //     ->options(fn () => Role::pluck('name', 'name')->toArray())
//                 //     ->multiple()
//                 //     ->visible(fn ($get) => $get('target') === 'role')
//                 //     ->columnSpanFull(),
//             ]);
//     }
// }

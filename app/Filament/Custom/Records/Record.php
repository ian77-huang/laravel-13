<?php

namespace App\Filament\Custom\Records;

use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

// class Record extends Page implements HasForms
class Record extends Page
{
    protected string $view = 'filament.admin.resources.pages.record';

    public ?string $previousUrl = null;

    public function mount(): void
    {
        // $this->form->fill();
    }

    // protected function fillForm(): void
    // {
    //     echo '<pre>';
    //     var_dump(2);
    //     echo '</pre>';
    //     $this->callHook('beforeFill');

    //     $this->form->fill();

    //     // $this->callHook('afterFill');
    // }

    // protected function beforeFill(): void
    // {
    //     echo '<pre>';
    //     var_dump('beforeFill');
    //     echo '</pre>';
    // }

    // // public function content(Schema $schema): Schema
    // // {
    // //     return $schema->components([
    // //         Form::make([
    // //             EmbeddedSchema::make('form'),
    // //         ])
    // //             ->id('form')
    // //             ->livewireSubmitHandler('save'),
    // //     ]);
    // // }
    // /**
    //  * @return TModel
    //  */
    // public function getRecord(): Model
    // {
    //     return $this->getBaseRecord();
    // }
}

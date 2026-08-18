<?php

namespace App\Filament\Custom\Actions;

use Filament\Actions\Action as FilamentAction;

class Action extends FilamentAction
{
    protected ?string $__permission = null;

    public function setPermission(string $name): static
    {
        $this->__permission = $name;

        return $this;
    }

    protected function getPermission(): string
    {
        return $this->__permission;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize(
            fn (): bool => $this->getPermission()
                ? auth()->user()->can($this->getPermission())
                : true
        );
    }
}

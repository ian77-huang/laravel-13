<?php

namespace App\Filament\Custom\Traits;

trait HasEditRecord
{
    protected function recordPermission(string $action): string
    {
        return $action.':'.class_basename(static::class);
    }

    protected function authorizeRecordUpdate($record): void
    {
        abort_unless(
            auth()->user()->can(
                $this->recordPermission('Update')
            ),
            403
        );
    }

    protected function authorizeAccess(): void
    {
        abort_unless(
            auth()->user()->can(
                $this->recordPermission('View')
            ),
            403
        );
    }
}

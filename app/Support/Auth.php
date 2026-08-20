<?php

namespace App\Support;

class Auth
{
    public static function can(string $permission): void
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->can($permission),
            403
        );
    }
}

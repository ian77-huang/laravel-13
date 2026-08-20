<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    // echo 'App.Models.User.'.$user;

    return (int) $user->id === (int) $id;
});

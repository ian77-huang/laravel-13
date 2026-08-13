<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;

class ChangePasswordController extends Controller
{
    public function get()
    {
        return view('frontend.user.change-password', [

        ]);
    }
}

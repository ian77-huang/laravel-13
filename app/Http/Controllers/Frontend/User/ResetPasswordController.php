<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;

class UserResetPasswordController extends Controller
{
    public function index()
    {
        return view('frontend.user.index', [

        ]);
    }
}

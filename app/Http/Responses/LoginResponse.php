<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return response()->json([
            'success' => true,
            'message' => trans('user.login.successful'),
            'user' => $request->user(),
        ], 201);
    }
}

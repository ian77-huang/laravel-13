<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        return response()->json([
            'success' => true,
            'message' => trans('user.registration.successful'),
            'user' => $request->user(),
        ], 201);
    }
}

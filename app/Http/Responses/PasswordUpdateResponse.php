<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\PasswordUpdateResponse as PasswordUpdateResponseContract;

class PasswordUpdateResponse implements PasswordUpdateResponseContract
{
    public function toResponse($request)
    {
        return response()->json([
            'success' => true,
            'message' => '密碼已成功更新！',
            'user' => $request->user(),
        ], 201);
    }
}

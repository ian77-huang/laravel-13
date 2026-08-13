<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function get()
    {

        $user = Auth::user();
        $profile = $user->profile;

        $maxSize = constants('maxSizeUserProfileAvatar') * 1024 * 1024;

        return view('frontend.user.profile', [
            'user' => $user,
            'profile' => $profile,
            'maxSize' => $maxSize,
        ]);
    }

    public function put(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users_profile')->ignore($request->user()->id, 'user_id')],
            'phone' => ['nullable', 'string', 'max:10'],
            'bio' => ['nullable', 'string'],
        ]);

        $profile = $request->user()->profile()->updateOrCreate(['user_id' => $request->user()->id], $validated);

        $created = $profile->wasRecentlyCreated;

        return response()->json([
            'success' => true,
            'message' => $created
                ? __('user.profile.create.success')   // 新增
                : __('user.profile.update.success'),  // 更新
            'data' => $profile,
        ], $created ? 201 : 200);
    }
}

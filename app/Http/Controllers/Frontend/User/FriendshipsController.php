<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendshipsController extends Controller
{
    public function getIndex()
    {
        return view('frontend.user.friendships', [
            'users' => User::withFriendStatus(Auth::id())
                ->get()
                ->toArray(),
        ]);
    }
}

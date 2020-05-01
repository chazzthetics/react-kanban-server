<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Str;

class UserObserver
{
    public function saving(User $user)
    {
        $split = Str::of($user->name)->lower()->explode(' ');

        if ($split->count() >= 2) {
            $username = $split[0][0].$split[1][0].'x'.rand(100, 999);
            $user->username = $username;
        } else {
            $username = $split[0][0].$split[0][1].'x'.rand(100, 999);
            $user->username = $username;
        }
    }
}

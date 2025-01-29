<?php

namespace App\Services;

use App\Models\User;

class TwoFactorService
{
    public function invalidate($userId)
    {
        $user = User::find($userId);

        if ($user && !$user->google2fa_verified) {
            $user->delete();
        }
    }
}

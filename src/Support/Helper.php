<?php

namespace Gurudin\LaravelAdmin\Support;

use Illuminate\Support\Facades\Auth;
use Gurudin\LaravelAdmin\Models\User;

class Helper
{
    /**
     * Is admin
     *
     * @param User $user
     *
     * @return bool
     */
    public static function isAdmin(User $user)
    {
        return in_array($user->email, config('admin.admin_email')) ? true : false;
    }
}

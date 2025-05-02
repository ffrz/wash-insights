<?php

use Illuminate\Support\Facades\Auth;

function allowed_roles($roles, $msg = 'FORBIDDEN', $code = 403)
{
    if ($user = Auth::user()) {
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        if (!in_array($user->role, $roles)) {
            abort($code, $msg);
        }
    }
}

<?php

namespace App\Business;

use App\Models\User;

class AbilitiesResolver
{
    public static function resolve(User $user, $device)
    {
        if ($user->role == 'client') {
            return static::resolveForClient($device);
        }
    }

    public static function resolveForClient($device)
    {
        return match ($device) {
            'watch' => [
                'establishment:show',
            ],
            default => [
                'establishment:show',
                'product:show',
                'orders:create',
                'cart:manage',
            ],
        };
    }
}

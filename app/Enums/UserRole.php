<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case USER = 'user';

    /**
     * Returns the hierarchical level of the role.
     * A lower number signifies a higher rank.
     */
    public function level(): int
    {
        return match ($this) {
            self::SUPER_ADMIN => 0,
            self::ADMIN => 1,
            self::USER => 2,
        };
    }
}

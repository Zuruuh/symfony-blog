<?php

namespace App\Common\Constant;

class UserRoles
{
    public const USER_ROLE  = 'ROLE_USER';
    public const ADMIN_ROLE = 'ROLE_ADMIN';
    public const SUPER_ADMIN_ROLE = 'ROLE_SUPER_ADMIN';

    public const ROLES = [
        self::USER_ROLE,
        self::ADMIN_ROLE,
        self::SUPER_ADMIN_ROLE,
    ];
}

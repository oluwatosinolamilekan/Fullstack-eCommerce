<?php

namespace App\Enums;

enum UserEnums: int
{
    case ADMIN = 1;
    case CUSTOMER = 2;
    
    public static function values(): array
    {
        return array_map(fn(self $case) => $case->value, self::cases());
    }
}

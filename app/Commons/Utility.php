<?php

declare(strict_types=1);

namespace App\Commons;

class Utility
{
    public static function required()
    {
        return '<span class="text-danger required-mark ms-1">*</span>';
    }
}

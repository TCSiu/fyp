<?php

declare(strict_types=1);

namespace App\Commons;

class Constants
{
    public const MODEL_REGEXP = '[a-z\-]+';

    public const PASSWORD_REGEXP = '(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}';
}

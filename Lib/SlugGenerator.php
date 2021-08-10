<?php

namespace Lib;

class SlugGenerator
{
    /**
     * Generate url slug for a string
     */
    public static function generate(string $data): string
    {
        $stub = substr(trim(preg_replace('/[^a-z0-9]+/i', '-', $data), '-'), 0, 248);
        $randInt = random_int(111111, 999999);
        return strtolower($stub . '-' . $randInt);
    }
}

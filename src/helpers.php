<?php

use BitrixChromeConsole\Console;

if (!function_exists('console')) {

    function console(mixed $var = '', string $tags = '', bool $trace = false)
    {
        Console::log($var, $tags, $trace);
    }
}
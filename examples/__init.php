<?php

require __DIR__ .'/../vendor/autoload.php';


if (!function_exists('dotEnvLocal')) {
    function dotEnvLocal(): array
    {
        $env = fopen(__DIR__ .'/../.env', 'r');

        $a = [];

        while (!feof($env)) {
            $explodeLine = explode('=', fgets($env));

            $name = $explodeLine[0];

            if (isset($explodeLine[1])) {
                $a[$name] = trim($explodeLine[1]);
                continue;
            }
            $a[$name] = null;
        }

        fclose($env);

        return $a;
    }
}

define('ENV', dotEnvLocal());
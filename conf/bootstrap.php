<?php

namespace ZDI\conf;

require_once __DIR__ . '/../vendor/autoload.php';

parse_str($argv[1], $arg);
$config = $arg['config'] ?? '';

return (new \ZDI\Inject(\ZDI\Magic::class))
        ->setDefinitions(
            [
                __DIR__ . '/config.php',
                __DIR__ . "/config.$config.php",
                __DIR__ . '/config.' . strtolower(gethostname()) . '.php'
            ]
        );

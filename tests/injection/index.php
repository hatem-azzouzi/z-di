<?php

echo "\n";
echo "bootstrapping..\n";

/* @var $inject \ZDI\Inject */
$inject = require_once __DIR__ . '/../../conf/bootstrap.php';

echo "\n";
echo "get Client definition\n";

/* @var $client \ZDI\tests\injection\Client */
$client = \ZDI\Inject::getDefinition(\ZDI\tests\injection\Client::class);

echo "\n";
echo "send function call\n";
$client->send("a test message");
echo "\n";

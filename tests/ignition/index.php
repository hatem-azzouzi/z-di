<?php

echo "\n";
echo "bootstrapping..\n";

/* @var $inject \ZDI\Inject */
$inject = require_once __DIR__ . '/../../conf/bootstrap.php';

echo "\n";
echo "get ClassA definition\n";

/* @var $classA ZDI\tests\ignition\ClassA */
$classA = \ZDI\Inject::getDefinition(ZDI\tests\ignition\ClassA::class);

echo "\n";
echo "foo function call\n";
$classA->foo("bar");
echo "\n";

<?php

/* @var $this \ZDI\Inject */

return array(

    ZDI\tests\ignition\ClassA::class => $this->instance(ZDI\tests\ignition\ClassA::class, 'hello'),

);

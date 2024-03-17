<?php

/* @var $this \ZDI\Inject */

return array(
    
    ZDI\tests\ignition\ClassA::class => $this->create(ZDI\tests\ignition\ClassB::class, 'foo', 'bar'),
    
);

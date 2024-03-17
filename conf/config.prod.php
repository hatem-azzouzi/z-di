<?php

/* @var $this \ZDI\Inject */

return array(
    
    ZDI\tests\ignition\ClassA::class => $this->create(ZDI\tests\ignition\ClassC::class, 'foo 1', 'bar 2'),
    
);

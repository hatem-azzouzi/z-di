<?php

namespace ZDI\tests\ignition;

class ClassB {

    /**
     * 
     * @param string $arg
     */
    public function __construct(string $arg = 'empty') {
        echo self::class . " instantiated: $arg\n";
    }
    
    /**
     * 
     * @param string $arg
     */
    function foo(string $arg = 'empty') {
        echo self::class . " function called: $arg\n";
    }
}
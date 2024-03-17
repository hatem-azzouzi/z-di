<?php

namespace ZDI\tests\injection;

class Email implements IMessage {

    /**
     * 
     * @param string $arg
     */
    public function __construct(string $arg) {
        echo self::class . " instantiated: $arg\n";
    }

    /**
     * 
     * @param string $message
     * @return bool
     */    
    function send(string $message) {
        echo self::class . " email sent: $message\n";
        return true;
    }
}
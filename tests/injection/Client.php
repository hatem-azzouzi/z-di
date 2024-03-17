<?php

namespace ZDI\tests\injection;

class Client {

    /**
     * 
     * @var IMesage
     */
    private $_service;
    
    /**
     * 
     * @param IMessage $service
     * @param string $arg
     */
    public function __construct(IMessage $service, $arg) {
        echo self::class . " instantiated: $arg\n";
        $this->_service = $service;
    }
    
    /**
     * 
     * @param string $message
     */
    function send(string $message) {
        $this->_service->send($message);
    }
}
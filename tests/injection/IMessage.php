<?php

namespace ZDI\tests\injection;

interface IMessage {
    /**
     * 
     * @param string $message
     * @return boolean
     */
    function send(string $message);
}
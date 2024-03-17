<?php

/* @var $this \ZDI\Inject */

return array(

    \ZDI\tests\injection\Email::class => $this->create(\ZDI\tests\injection\Email::class, 'email'),

    \ZDI\tests\injection\Text::class => $this->create(\ZDI\tests\injection\Text::class, 'text'),

    \ZDI\tests\injection\Client::class => $this->create(\ZDI\tests\injection\Client::class, 'client')
        ->inject(\ZDI\tests\injection\Email::class),

// eager loading example
//    \ZDI\tests\injection\Client::class => $this->create(\ZDI\tests\injection\Client::class, $this->instance(\ZDI\tests\injection\Email::class, 'text'), 'client'),

);

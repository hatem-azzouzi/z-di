<?php

namespace ZDI;

class Proxy {
    
    /**
     * 
     * @var type
     */
    protected $_args;
    /**
     * 
     * @var string
     */
    protected $_className;
    /**
     * 
     * @var type
     */
    protected $_instance;
    
    /**
     * 
     * @param string $load
     * @param string $classeName
     * @param array $args
     */
    public function __construct(string $load, string $classeName, ...$args) {
        $this->_className = $classeName;
        $this->_args = $args;
        if ($load === Inject::EAGER_LOAD) {
            $this->_instance = new $this->_className(...$this->_args);
        }
    }
    
    /**
     * 
     * @param string $definition
     */
    public function inject(string $definition) {
        array_unshift($this->_args , $definition);
        return $this;
    }
    
}

<?php

namespace ZDI;

class Inject {
    
    const LAZY_LOAD = 'ZDI\LAZY_LOAD';
    const EAGER_LOAD = 'ZDI\EAGER_LOAD';
    
    /**
     * 
     * @var array
     */
    private static $_definitions = [];
    /**
     * 
     * @var array
     */
    private $_proxies = [];
    /**
     * 
     * @var string
     */
    private $_proxyClassName;
    
    /**
     * 
     * @param string $proxyClassName
     */
    public function __construct(string $proxyClassName) {
        $this->_proxyClassName = $proxyClassName;
    }
    
    /**
     * 
     * @param string $className
     * @param type $args
     * @return Proxy
     */
    public function create(string $className, ...$args) {
        $this->_proxies[$className] = new $this->_proxyClassName(self::LAZY_LOAD, $className, ...$args);
        return $this->_proxies[$className];
    }
    
    /**
     * 
     * @param string $className
     * @param type $args
     * @return Proxy
     */
    public function instance(string $className, ...$args) {
        $this->_proxies[$className] = new $className(...$args);
        return $this->_proxies[$className];
    }
    
    /**
     * 
     * @param array $configs
     * @return self
     */
    public function setDefinitions(array $configs) {
        foreach ($configs as $pathname) {
            if (file_exists($pathname)) {
                $definition = require($pathname);
                if (is_array($definition)) {
                    self::$_definitions = array_replace_recursive(self::$_definitions, $definition);
                }
            }
        }
        return $this;
    }

    /**
     * 
     * @param string $name
     * @return null|mixed
     */
    public static function getDefinition(string $name) {
        if (self::$_definitions[$name]) {
            if (is_callable(self::$_definitions[$name], false)) {
                return self::$_definitions[$name]();
            }
            return self::$_definitions[$name];
        }
        return null;
    }

}

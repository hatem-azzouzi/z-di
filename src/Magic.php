<?php

namespace ZDI;

class Magic extends Proxy {
    
    /**
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    function __call(string $name, array $arguments) {
        if ($this->_instance === null) {
            foreach($this->_args as &$arg) {
                if (Inject::getDefinition($arg)) {
                    $definition = Inject::getDefinition($arg);
                    if ($definition instanceof Magic) {
                        $arg = new $definition->_className(...$definition->_args);
                    } else {
                        $arg = $definition;
                    }
                }                
            }
            $this->_instance = new $this->_className(...$this->_args);            
        }

        return call_user_func_array(
                array($this->_instance, $name),
                $arguments
            );
    }    
}

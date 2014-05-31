<?php
namespace Phapp\DI;

class FactoryDefault extends \Phalcon\DI\FactoryDefault {
    
    /**
     * Override default get to allow service factories
     * 
     * @param string $name
     * @param string $parameters
     * @return object
     */
    public function get($name, $parameters = null) {
        $service = parent::get($name, $parameters);
        if ($service instanceof ServiceFactoryInterface) {
            return $service->createService();
        }
        return $service;
    }
}

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

    /**
     * Allows to obtain a shared service using the array syntax.
     * Alias for \Phalcon\Di::getShared()
     *
     * <code>
     * 	var_dump($di['request']);
     * </code>
     *
     * @param string $name
     * @return mixed
     */
    public function offsetGet($property) {
        $service = parent::offsetGet($property);
        if ($service instanceof ServiceFactoryInterface) {
            return $service->createService();
        }
        return $service;
    }

    /**
     * Magic method to get or set services using setters/getters
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, $arguments = null) {
        $service = parent::__call($method, $arguments);
        if ($service instanceof ServiceFactoryInterface) {
            return $service->createService();
        }
        return $service;
    }

}

<?php
namespace Phapp\DI;

abstract class AbstractServiceFactory implements ServiceFactoryInterface {
    
    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;

    /**
     * Set dependency injection
     * 
     * @param \Phalcon\DiInterface $dependencyInjector
     */
    public function setDI($dependencyInjector) {
        $this->di = $dependencyInjector;
    }
    
    /**
     * Get dependency injection
     * 
     * @return \Phalcon\DiInterface
     */
    public function getDI() {
        return $this->di;
    }
}

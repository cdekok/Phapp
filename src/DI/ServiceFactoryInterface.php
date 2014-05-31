<?php
namespace Phapp\DI;

use Phalcon\DI\InjectionAwareInterface;

interface ServiceFactoryInterface extends InjectionAwareInterface {
    
    /**
     * Create service
     * 
     * @return mixed
     */
    public function createService();
}

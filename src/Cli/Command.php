<?php

namespace Phapp\Cli;

use Phalcon\DI\InjectionAwareInterface;
use Phalcon\DiInterface;
use Symfony\Component\Console\Command\Command as SfCommand;

abstract class Command extends SfCommand implements InjectionAwareInterface {
    
    /**     
     * @var DiInterface
     */
    protected $di;
    
    /**
    * Sets the dependency injector
    *
    * @param DiInterface $dependencyInjector
    */
   public function setDI($dependencyInjector) {
       $this->di = $dependencyInjector;
   }

   /**
    * Returns the internal dependency injector
    *
    * @return DiInterface
    */
   public function getDI() {
       return $this->di;
   }
}

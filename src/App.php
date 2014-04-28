<?php
namespace Phapp;

class App {
    
    /**
     * Phalcon DI
     * @var \Phalcon\DiInterface
     */
    private $di;
    
    /**
     * Appliction config
     * @var array
     */
    private $config;
    
    /**
     * Construct application
     * @param array $config
     */
    public function __construct(array $config) 
    {
        $this->config = $config;
    }
    
    /**
     * Run application
     */
    public function run()
    {
        try {
            $this->bootstrap();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }
    
    /**
     * Bootstrap application
     */
    private function bootstrap()
    {
        $app = new \Phalcon\Mvc\Application();
        $app->registerModules($this->config['modules']);
    }

    private function setupRoutes()
    {
        $router = new \Phalcon\Mvc\Router();
        $router->setDefaultModule('\\Cept\\Blog');
        foreach ($this->config['routes'] as $route => $config) {
            $router->add($route, $config);
        }
    }
    
    /**
     * Get DI
     * @return \Phalcon\DiInterface
     */
    private function getDi()
    {
        if ($this->di) {
            return $this->di;
        }
        return $this->di = new \Phalcon\DI\FactoryDefault();
    }
}
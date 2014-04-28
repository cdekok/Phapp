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
            $this->setupConfig();
            $this->setupRoutes();
            $app = new \Phalcon\Mvc\Application($this->getDi());
            $app->registerModules($this->config['modules']);        
            echo $app->handle()->getContent();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }    

    /**
     * Retrieve all config from modules
     */
    private function setupConfig()
    {
        foreach ($this->config['modules'] as $config) {
            $module = new \ReflectionClass($config['className']);
            $moduleConfig = require dirname($module->getFileName()).'/../config/config.php';
            $this->config = array_merge($this->config, $moduleConfig);
        }
    }
    
    /**
     * Setup routing
     */
    private function setUpRoutes()
    {        
        $router = new \Phalcon\Mvc\Router();
        $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
        // default routes
        if (isset($this->config['defaultRoute'])) {
            if (isset($this->config['defaultRoute']['module'])) {
                $router->setDefaultModule($this->config['defaultRoute']['module']);
            }
            if (isset($this->config['defaultRoute']['controller'])) {
                $router->setDefaultController($this->config['defaultRoute']['controller']);
            }
            if (isset($this->config['defaultRoute']['action'])) {
                $router->setDefaultAction($this->config['defaultRoute']['action']);
            }            
        }
        // module routes
        foreach ($this->config['routes'] as $route => $config) {
            $router->add($route, $config);
        }       
        $this->getDi()['router'] = $router;
    }
    
    /**
     * Setup db 
     */
    private function setUpDb()
    {
        if (!isset($this->config['db'])) {
            return;
        }        
        $config = new \Doctrine\DBAL\Configuration();
        $db = \Doctrine\DBAL\DriverManager::getConnection($this->config['db'], $config);
        $this->getDi()['db'] = $db;
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
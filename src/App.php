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
        if (php_sapi_name() === 'cli') {
            $this->cliRequest();
        } else {
            $this->webRequest();
        }
    }    
    
    /**
     * Handle web request
     */
    protected function webRequest() 
    {
        try {
            $this->setupConfig();
            $this->setupRoutes();
            $this->setupView();
            $app = new \Phalcon\Mvc\Application($this->getDi());             
            $app->registerModules($this->config['modules']);                
            echo $app->handle()->getContent();
        } catch ( \Exception $exc) {
            echo $exc->getMessage();            
            if ($this->config['debug'] === true) {
                echo '<pre>';
                echo $exc->getTraceAsString();
                echo '</pre>';
            }
        }
    }

    /**
     * Handle command line request
     */
    protected function cliRequest()
    {
        $this->setupConfig();
        if (!isset($this->config['commands']) OR empty($this->config['commands'])) {
            return;
        }
        $app = new \Symfony\Component\Console\Application('phapp');
        foreach ($this->config['commands'] as $cmd) {
            $cliCmd = new $cmd;
            if ($cliCmd instanceof \Phalcon\DI\InjectionAwareInterface) {
                $cliCmd->setDI($this->getDi());
            }
            $app->add($cliCmd);
        }   
        $app->run();
    }

    /**
     * Configure view in DI
     */
    private function setupView()
    {
        $config = $this->config;
        $view = new \Phapp\Mvc\View();    
        if (isset($config['views']['theme'])) {
            $view->setThemePath($config['views']['theme']);
        }        
        if (isset($this->config['views']['viewsDir'])) {
            $view->setViewsDir($this->config['views']['viewsDir']);
        }
        if (isset($this->config['views']['layoutDir'])) {            
            $view->setLayoutsDir($this->config['views']['layoutDir']);
        }
        if (isset($this->config['views']['layoutDir'])) {
            $view->setLayout($this->config['views']['layout']);
        }
        $this->getDi()['view'] = $view;
    }

    /**
     * Retrieve all config from modules
     */
    private function setupConfig()
    {
        foreach ($this->config['modules'] as $config) {
            $module = new \ReflectionClass($config['className']);
            $moduleConfig = require dirname($module->getFileName()).'/../config/config.php';
            $this->config = array_merge_recursive($this->config, $moduleConfig);
        }
        $this->setUpDb();
    }
    
    /**
     * Setup routing
     */
    private function setUpRoutes()
    {        
        $group = new \Phalcon\Mvc\Router\Group();
        //$group->setPrefix('/{lang}');
        
        // default routes
        if (isset($this->config['defaultRoute'])) {
            foreach ($this->config['defaultRoute'] as $key => $val) {
                $method = 'set'.$key;
                if (method_exists($group, $method)) {
                    $group->$method($val);                
                }
            }         
        }
        
        // module routes
        foreach ($this->config['routes'] as $name => $route) {
            $group->add($route['route'], $route['params'])->setName($name);
        }   
                
        $router = new \Phalcon\Mvc\Router(false);        
        $router->setUriSource(\Phalcon\Mvc\Router::URI_SOURCE_SERVER_REQUEST_URI);
        // Setup 404
        if (isset($this->config['notFoundRoute'])) {            
            $router->notFound($this->config['notFoundRoute']);
        }        
        $router->mount($group);        
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
        $this->getDi()->set('db', $db);
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
        $this->di = new DI\FactoryDefault();
        // Add factories to the DI
        if (isset($this->config['factories'])) {
            foreach ($this->config['factories'] as $name => $definition) {
                $this->di->set($name, $definition, true);
            }
        }                
        // Config for session data
        $this->di->set('session', function(){
            $sessionSettings = [];
            if (isset($this->config['session'])) {
                $sessionSettings = $this->config['session'];
            }
            $session = new \Phalcon\Session\Adapter\Files($sessionSettings);
            $session->start();
            return $session;
        }, true);
        // Set base url for url helper       
        $this->di->set('url', function(){
            $url = new \Phalcon\Mvc\Url();
            $url->setBaseUri($this->config['baseUrl']);
            return $url;
        });
        return $this->di;
    }
}
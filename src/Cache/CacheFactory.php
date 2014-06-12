<?php
namespace Phapp\Cache;

class CacheFactory extends \Phapp\DI\AbstractServiceFactory implements \Phapp\DI\ServiceFactoryInterface {

    /**
     * @var \Cept\User\Model\RoleRepo
     */
    protected $cache;

    /**
     * Create user repo
     *
     * @return \Cept\User\Model\UserRepo
     */
    public function createService() {
        if (!$this->cache) {
            $config = $this->getDI()->get('config')->toArray();
            $frontendClass = $config['cache']['frontend']['className'];
            $frontendOptions = null;
            if (isset($config['cache']['frontend']['options'])) {
                $frontendOptions = $config['cache']['frontend']['options'];
            }
            $frontend = new $frontendClass($frontendOptions);
            $backendClass = $config['cache']['backend']['className'];
            $backendOptions = null;
            if (isset($config['cache']['backend']['options'])) {
                $backendOptions = $config['cache']['backend']['options'];
            }
            $this->cache = new $backendClass($frontend, $backendOptions);
        }
        return $this->cache;
    }
}
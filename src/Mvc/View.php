<?php
namespace Phapp\Mvc;

class View extends \Phalcon\Mvc\View implements ThemeInterface {

    /**
     * Theme path
     * 
     * @var string
     */
    private $themePath;
    
    /**
    * @param array $engines
    * @param string $viewPath
    * @param boolean $silence
    * @param boolean $mustClean
    * @param \Phalcon\Cache\BackendInterface $cache
     */
    protected function _engineRender($engines, $viewPath, $silence, $mustClean, $cache) {        
        
        // Layout / View override        
        $override = $this->getViewOverride($engines, $viewPath);
        if ($override) {                                               
            $originalViewDir = $this->getViewsDir();
            $this->setViewsDir($override);
            $return = parent::_engineRender($engines, $viewPath, $silence, $mustClean, $cache);
            $this->setViewsDir($originalViewDir);                
            return $return;
        }
        
        return parent::_engineRender($engines, $viewPath, $silence, $mustClean, $cache);                        
    }
    
    /**
     * Check if there is a view override in the theme path and return the view path if there is
     * 
     * @param array $engines
     * @param string $viewPath
     * @return null|string views dir for override
     */
    private function getViewOverride(array $engines, $viewPath) {
        // layout override
        if ($this->getCurrentRenderLevel() === \Phalcon\Mvc\View::LEVEL_LAYOUT) {
            $overrideViewDir = $this->getThemePath();
        }
        // View override
        else if ($this->getCurrentRenderLevel() === \Phalcon\Mvc\View::LEVEL_ACTION_VIEW) {            
            $overrideViewDir = $this->getThemePath().$this->getDI()->get('dispatcher')->getModuleName().DIRECTORY_SEPARATOR;            
        } else {
            return;
        }
        foreach ($engines as $extension => $engine) {
            $override = $overrideViewDir.$viewPath.$extension;
            if (is_readable($override)) {
                return $overrideViewDir;
            }
        }       
    }

    /**
     * Set theme path
     * @param string $path
     */
    public function setThemePath($path)
    {
        $this->themePath = $path;
    }

    /**
     * Get theme path
     * @return string
     */
    public function getThemePath()
    {
        return $this->themePath;
    }    
}
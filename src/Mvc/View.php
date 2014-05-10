<?php
namespace Phapp\Mvc;

class View extends \Phalcon\Mvc\View {
    
    /**
    * @param array $engines
    * @param string $viewPath
    * @param boolean $silence
    * @param boolean $mustClean
    * @param \Phalcon\Cache\BackendInterface $cache
     */
    protected function _engineRender($engines, $viewPath, $silence, $mustClean, $cache) {        
        // Layout override
        if ($this->getCurrentRenderLevel() !== \Phalcon\Mvc\View::LEVEL_LAYOUT) {
            return parent::_engineRender($engines, $viewPath, $silence, $mustClean, $cache);                        
        }
        foreach ($engines as $extension => $engine) {
            if (!$engine instanceof View\Engine\ThemeInterface) {
                continue;
            }
            $layout = $engine->getThemePath().$viewPath.$extension;
            if (is_readable($layout)) {
                $originalViewDir = $this->getViewsDir();
                $this->setViewsDir($engine->getThemePath());
                parent::_engineRender($engines, $viewPath, $silence, $mustClean, $cache);
                $this->setViewsDir($originalViewDir);
            }
        }
    }        
}
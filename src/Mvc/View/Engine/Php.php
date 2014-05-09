<?php
namespace Phapp\Mvc\View\Engine;

class Php extends \Phalcon\Mvc\View\Engine\Php
{
    /**
     * Theme path
     * @var string
     */
    private $themePath;

    /**
    * Renders a view using the template engine
    *
    * @param string $path
    * @param array $params
    * @param boolean $mustClean
    */
    public function render($path, $params, $must_clean = null)
    {
        $themePath = str_replace(
            $this->di->getView()->getViewsDir(),
            $this->getThemePath().$this->getDI()->get('dispatcher')->getModuleName().DIRECTORY_SEPARATOR,
            $path
        );
        if (is_readable($themePath)) {
            $path = $themePath;
        }

        return parent::render($path, $params, $must_clean);
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
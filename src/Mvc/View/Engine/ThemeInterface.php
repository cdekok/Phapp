<?php
namespace Phapp\Mvc\View\Engine;

interface ThemeInterface {
    
    /**
     * Set theme path
     * @param string $path
     */
    public function setThemePath($path);

    /**
     * Get theme path
     * @return string
     */
    public function getThemePath();
}

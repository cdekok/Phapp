<?php
namespace Phapp\Mvc;

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

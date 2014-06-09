<?php
namespace Phapp\Hydrate;

interface HydrateInterface {
    
    /**
     * Hydrate object
     * 
     * @param array $data
     * @return mixed
     */
    public function hydrate(array $data);
    
    /**
     * Get object as array
     * 
     * @return array
     */
    public function extract();
}
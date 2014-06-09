<?php
namespace Phapp\Hydrate;

trait HydrateTrait {
    
    /**
     * Hydrate user object
     * 
     * @param array $data
     * @return \Cept\User\Model\User
     */
    public function hydrate(array $data) {
        foreach ($data as $key => $value) {
            $def = 'set'.ucfirst($key);
            $this->{$def}($value);
        }
        return $this;
    }
    
    /**
     * Get object as array
     * 
     * @return array
     */
    public function extract() {
        return get_object_vars($this);
    }
}
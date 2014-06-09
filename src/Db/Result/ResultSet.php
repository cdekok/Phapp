<?php

namespace Phapp\Db\Result;

class ResultSet implements \Iterator {

    /**
     * DB Statement
     * @var \Doctrine\DBAL\Driver\Statement
     */
    protected $stmt;

    /**
     * Query builder
     * @var \Doctrine\DBAL\Query\QueryBuilder
     */
    protected $qb;
    
    /**
     * Rewind counter
     * @var integer
     */
    protected $run = 0;

    /**
     * Current row
     * @var mixed
     */
    protected $row;
    
    /**
     * Key
     * @var integer
     */
    protected $key;

    /**
     * Hydrate object
     * @var \Cept\User\Hydrate\HydrateInterface
     */
    protected $hydrator;
    
    /**
     * Cache the result set in memory so you iterate over it more then once
     * do not enable on large result sets
     * 
     * @var boolean 
     */
    protected $cacheResult = false;
    
    /**
     * Memory cache
     * @var array
     */
    protected $cache = [];

    /**
     * 
     * @param \Doctrine\DBAL\Driver\Statement $stmt
     * @param \Cept\User\Model\Cept\User\Hydrate\HydrateInterface $hydrate
     */
    public function __construct(\Doctrine\DBAL\Query\QueryBuilder $qb, \Cept\User\Hydrate\HydrateInterface $hydrate = null) {
        $this->qb = $qb;
        $this->hydrator = $hydrate;
    }
    /**
     * Return the current element
     * 
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current() {
        if ($this->run > 1 && $this->cacheResult) {
            return $this->cache[$this->key];
        }
        return $this->row;
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next() {         
        $row = $this->stmt->fetch();        
        // Do some hydration
        if ($this->hydrator && $row) {            
            $obj = clone $this->hydrator;
            $this->row = $obj->hydrate((array)$row);            
        } else {
            $this->row = $row;
        }        
        if ($this->cacheResult && $row) {
            $this->cache[$this->key] = $this->row;
        }
        $this->key++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return scalar scalar on success, or <b>NULL</b> on failure.
     */
    public function key() {
        return $this->key;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function valid() {
        if ($this->run > 1 && $this->cacheResult) {
            return isset($this->cache[$this->key]);
        }
        if ($this->row) {
            return true;
        }
        return false;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        $this->run++;
        $this->key = 0;
        if ($this->run === 1) {
            $this->stmt = $this->qb->execute();
            $this->next();                    
        }
        if ($this->run > 1 && !$this->cacheResult) {
            throw new Exception\RewindException('You can only iterate once, or enable the cache');
        }
        if ($this->run > 1 && $this->cacheResult) {
            reset($this->cache);
        }
    }
    
    /**
     * Get the cache resul set state
     * 
     * @return boolean
     */
    public function getCacheResult() {
        return $this->cacheResult;
    }

    /**
     * Cache result in memory so the result set can be iterated more then once
     * 
     * @param boolean $cacheResult
     * @return \Phapp\Db\Result\ResultSet
     */
    public function setCacheResult($cacheResult) {
        $this->cacheResult = $cacheResult;
        return $this;
    }
}

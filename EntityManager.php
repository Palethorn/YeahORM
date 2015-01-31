<?php

namespace YeahOrm;

class EntityManager {
    
    private $dbConfig = null;
    
    public function __construct(\YeahOrm\DatabaseConfigInterface $dbConfig) {
        $this->dbConfig = $dbConfig;
    }
    
    public function getModel($class) {
        return new $class($this->dbConfig);
    }
}

<?php

namespace Yeah\ORM;

class EntityManager {

    private $dbConfig = null;

    public function __construct(\Yeah\DB\DatabaseConfigInterface $dbConfig) {
        $this->dbConfig = $dbConfig;
    }

    public function getRepository($model_class) {
        return new $model_class::$repository($this->dbConfig, $model_class::$table, $model_class);
    }
}

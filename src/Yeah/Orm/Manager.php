<?php
namespace Yeah\Orm;

use Yeah\Dbal\Query;


class Manager {
    private $connection;

    public function __construct(\Yeah\Dbal\Connection $connection, $schema_dir) {
        $this->connection = $connection;   
        $this->schema_dir = $schema_dir;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query() {
        return new Query($this->connection, $this->schema_dir);
    }
}

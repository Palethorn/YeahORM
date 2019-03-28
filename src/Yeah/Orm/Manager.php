<?php
namespace Yeah\Orm;

use Yeah\Dbal\Query;


class Manager {
    private $connection;
    private $schema;

    public function __construct(\Yeah\Dbal\Connection $connection, $schema_dir) {
        $this->connection = $connection;
        $this->schema_dir = $schema_dir;
        $this->schema = array();
    }

    public function getConnection() {
        return $this->connection;
    }

    public function loadSchema($table) {
        if(isset($this->schema[$table])) {
            return $this->schema[$table];
        }

        return $this->schema[$table] = require $this->schema_dir . '/' . $table . '.php';
    }

    public function select(\Yeah\Dbal\Select $select, $params = array()) {
        $stmt = $this->connection->prepare($select->getSql());
        
        foreach($params as $key => $value) {
            $stmt->bindParam(':' . $key, $value);
        }

        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_NUM);
        $hydrated_result = array();
        $mappings = $select->getMappings();

        foreach($results as $result) {
            $i = 0;

            foreach($mappings as $alias => $mapping) {
                $schema = $this->loadSchema($mapping['table']);
                $class = '\\' . $schema['namespace'] . '\\' . $schema['class'];
                $o = new $class();

                foreach($schema['fields'] as $name => $def) {
                    $method = 'set' . camelize($name);
                    $o->$method($result[$i]);
                    $i = $i + 1;
                }

                $hydrated_result[] = $o;
            }
        }

        return $hydrated_result;
    }
}

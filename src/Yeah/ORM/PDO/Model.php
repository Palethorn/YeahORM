<?php

namespace Yeah\ORM\PDO;

/**
 * Provides abstract implementation of database model class
 *
 * @author David Cavar
 */
abstract class Model {
    
    private $db_adapter = null;

    /**
     * Saves object properties to database
     */
    public function save() {
        if($this->exists()) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    /**
     * Inserts new row into database
     */
    private function insert() {
        $values = array();
        $columns = array();
        foreach($this->schema as $property => $options) {
            if(isset($this->$property)) {
                $columns[] = $property;
                if($options['pdo_type'] == \PDO::PARAM_INT) {
                    $values[] = $this->$property;
                } else {
                    $values[] = "'" . $this->$property . "'";
                }
            }
        }
        $query = 'insert into ' . $this->table . '(' . implode(',', $columns) . ') ' . 'values(' . implode(',', $values) . ')';
        $this->db_adapter->query($query);
        $this->id = $this->db_adapter->lastInsertId();
    }

    /**
     * Updates existing row in database
     */
    public function update() {
        $columns = array();
        foreach($this->schema as $property => $options) {
            if($property === 'id') {
                continue;
            }
            if(isset($this->$property)) {
                if($options['pdo_type'] == \PDO::PARAM_INT) {
                    $columns[] = $property . '=' . $this->$property;
                } else {
                    $columns[] = $property . '=\'' . $this->$property . '\'';
                }
            }
        }
        $query = 'update ' . $this->table . ' set ' . implode(',', $columns) . ' where id = ' . $this->id;
        $this->db_adapter->query($query);
    }

    /**
     * Deletes current object from database
     */
    public function delete() {
        if(isset($this->id)) {
            $q = 'delete from ' . $this->table . ' where id=' . $this->id;
            $this->db_adapter->query($q);
        }
    }

    /**
     * Checks if a record already exists
     *
     * @return boolean
     */
    public function exists() {
        if(!isset($this->id)) {
            return false;
        }
        $query = 'select id from ' . $this->table . ' where id=' . $this->id;
        if($this->db_adapter->query($query)->fetch(\PDO::FETCH_ASSOC)) {
            return true;
        }
        return false;
    }

}

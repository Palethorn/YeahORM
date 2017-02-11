<?php
namespace Yeah\ORM\PDO;

class Repository {

    protected $order_by = null;
    protected $limit = null;
    protected $offset = null;

    /**
     * Creates new PdoModel instance
     *
     * @param mixed $options PdoModel options
     */
    public function __construct(\Yeah\DB\DatabaseConfigInterface $config, $table_name, $model_class) {
        $this->db_adapter = new \Yeah\DB\PDO\Connection($config);
        $this->table_name = $table_name;
        $this->model_class = $model_class;
        $this->schema = $config->getSchema($table_name);
    }

    /**
     * Finds all records from table
     *
     * @param bool $return_as_object Should the result be fetched as object or
     * plain array
     * @return mixed
     * @throws \Exception
     */
    public function findAll($return_as_object = true) {
        $query = 'select * from ' . $this->table_name;

        if($this->order_by) {
            $query .= ' ORDER BY ' . $this->order_by;
        }

        if($this->order_by) {
            $query .= ' LIMIT ' . $this->limit;
        }

        if($this->offset) {
            $query .= ' OFFSET ' . $this->offset;
        }

        try {
            $r = $this->db_adapter->query($query);
            if($return_as_object) {
                return $r->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $this->model_class);
            }
            return $r->fetchAll(\PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new \Exception('Error in SQL query!', 500, null);
        }
    }

    /**
     * Finds record by specified table field and value
     *
     * @param string $field Table field
     * @param string $arg Field value
     * @param boolean $return_as_object Should the result be bound to model or
     * returned as array
     * @return mixed
     * @throws \Exception
     */
    public function findBy($field, $arg, $return_as_object = true) {
        if($field != 'id') {
            $arg = '\'' . $arg . '\'';
        }
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE ' . $field . '=' . $arg . ' LIMIT 0, 1';
        try {
            $r = $this->db_adapter->query($query);
            if($return_as_object) {
                $r->setFetchMode(\PDO::FETCH_INTO, $this);
                return $r->fetch();
            } else {
                return $r->fetch(\PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            throw new \Exception('Error in SQL query!', 500, null);
        }
    }

    /**
     *
     * @param string $method Method name
     * @param mixed $args Method arguments
     * @return Yeah\Fw\Db\PdoModel
     */
    public function __call($method, $args) {
        if(strpos($method, 'findBy') == 0) {
            $field = strtolower(str_replace('findBy', '', $method));
            return $this->findBy($field, $args[0]);
        }
    }

    public function setLimit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function setOffset($offset) {
        $this->offset = $offset;
        return $this;
    }
}

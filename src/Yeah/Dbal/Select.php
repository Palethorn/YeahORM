<?php
namespace Yeah\Dbal;

class Select {
    private 
        $select,
        $from, 
        $joins, 
        $and_where, 
        $or_where, 
        $order_by, 
        $offset, 
        $limit,
        $tables,
        $fields;

    public function __construct() {
        $this->select = array();
        $this->from = array();
        $this->joins = array();
        $this->and_where = array();
        $this->or_where = array();
        $this->order_by = array();
        $this->tables = array();
        $this->fields = array();
    }

    public function addSelect($select) {
        $this->select[] = $select;
        return $this;
    }

    public function addFrom($from) {
        $this->from[] = $from;
        return $this;
    }

    public function addJoin($type, $table, $on) {
        $this->joins[] = array(
            'type' => $type,
            'table' => $table,
            'on' => $on
        );

        return $this;
    }

    public function andWhere($where) {
        $this->and_where[] = $where;
        return $this;
    }
    
    public function orWhere($where) {
        $this->or_where[] = $where;
        return $this;
    }

    public function addOrderBy($order_by) {
        $this->order_by[] = $order_by;
        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }

    public function getSql() {
        $sql = $this->prepareSelect();
        $sql .= $this->prepareFrom();
        $sql .= $this->prepareJoins();
        $sql .= $this->prepareWhere();

        return $sql;
    }

    private function prepareSelect() {
        if(!count($this->select)) {
            return 'SELECT *';
        }

        $frag = 'SELECT ';

        foreach($this->select as $select) {
            $frag .= $select . ', ';
        }

        return \substr($frag, 0, -2);
    }

    private function prepareFrom() {
        $frag = ' FROM ';

        foreach($this->from as $from) {
            $frag .= $from . ', ';
        }

        return \substr($frag, 0, -2);
    }

    private function prepareJoins() {
        if(!count($this->joins)) {
            return '';
        }

        $frag = ' ';

        foreach($this->joins as $join) {
            $frag .= $join['type'] . ' ' . $join['table'] . ' ON '. $join['on'];
        }

        return $frag;
    }

    private function prepareWhere() {
        if(!($and_where_count = count($this->and_where)) && !($or_where_count = count($this->or_where))) {;
            return '';
        }

        $frag = ' WHERE ';

        if($and_where_count) {
            foreach($this->and_where as $and_where) {
                $frag .= $and_where . ' AND ';
            }

            $frag = \substr($frag, 0, -5) . ' OR ';
        }
        
        foreach($this->or_where as $or_where) {
                $frag .= $or_where . ' OR ';
        }

        return \substr($frag, 0, -4);
    }

    public function getMappings() {

        foreach($this->from as $table) {
            $parts = explode(' ', $table);
            $mapping[$parts[1]] = array('table' => $parts[0], 'fields' => array());
        }

        foreach($this->select as $select) {
            if($select == '*') {
                break;
            }

            $parts = explode('.', $select);
            $mapping[$parts[0]]['fields'][] = $parts[1];
        }

        foreach($this->joins as $join) {
            $parts = explode(' ', $join['table']);
            $mapping[$parts[1]] = array('table' => $parts[0], 'fields' => array());
        }

        return $mapping;
    }
}
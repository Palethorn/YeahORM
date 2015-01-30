<?php

namespace YeahOrm;

/**
 * Implements initialization of database adapter
 * 
 * @author David Cavar
 */
class PdoAdapter implements \YeahOrm\AdapterInterface {

    /**
     * Initializes database adapter
     * 
     * @param mixed $options Database options
     */
    public function init($options) {
        PdoConnection::configure($options['dsn'], $options['db_user'], $options['db_password']);
        PdoModel::configure($options['db_schema_path']);
    }

}

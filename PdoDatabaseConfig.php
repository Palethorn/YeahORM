<?php

namespace YeahOrm;

class PdoDatabaseConfig extends \YeahOrm\DatabaseConfig {

    public function getDsn() {
        return 'mysql:host=' . $this->getHost() . ';dbname=' . $this->getDatabase() . ';charset=' . $this->getCharset();
    }

    public function getSchema() {
        
    }
}

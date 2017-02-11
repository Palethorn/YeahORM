<?php

namespace Yeah\DB\PDO;

class DatabaseConfig extends \Yeah\DB\DatabaseConfig {

    public function getDsn() {
        return 'mysql:host=' . $this->getHost() . ';dbname=' . $this->getDatabase() . ';charset=' . $this->getCharset();
    }
}

<?php
namespace Yeah\Dbal\Mysql;

class Connection extends \PDO {
    public function __construct($host, $port, $dbname, $username, $password, $charset = 'utf8') {
        $conn_string  = sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $dbname);

        parent::__construct($conn_string, $username, $password, array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => true,
            \PDO::ATTR_STRINGIFY_FETCHES => false
        ));
    }
}
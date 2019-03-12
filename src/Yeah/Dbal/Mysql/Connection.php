<?php
namespace Yeah\Dbal\Mysql;

class Connection extends \PDO implements \Yeah\Dbal\Connection {
    public function __construct($dbname, $username, $password, $host = '127.0.0.1', $port = 3306, $charset = 'utf8') {
        $conn_string  = sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $dbname);

        parent::__construct($conn_string, $username, $password, array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => true,
            \PDO::ATTR_STRINGIFY_FETCHES => false
        ));
    }
}
<?php

namespace YeahOrm;

class DatabaseConfig implements \YeahOrm\DatabaseConfigInterface {

    private $username = null;
    private $password = null;
    private $host = null;
    private $database = null;
    private $charset = null;
    private $persistent = true;
    private $error_mode = 2;
    private $emulate_prepares = false;
    private $stringify_fetches = false;
    private $schema = null;

    public function importFromFile($file) {
        $config = require_once $file;
        $this->importFromArray($config);
    }

    public function importFromArray($config) {
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->host = $config['host'];
        $this->database = $config['database'];
        $this->charset = $config['charset'];

        $this->presistent = isset($config['presistent']) ? $config['presistent'] : true;
        $this->error_mode = isset($config['error_mode']) ? $config['error_mode'] : \PDO::ERRMODE_EXCEPTION;
        $this->stringify_fetches = isset($config['stringify_fetches']) ? $config['stringify_fetches'] : false;
        $this->emulate_prepares = isset($config['emulate_prepares']) ? $config['emulate_prepares'] : false;
        
        $this->schema = require $config['schema_path'];
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getHost() {
        return $this->host;
    }

    public function getDatabase() {
        return $this->database;
    }

    public function getCharset() {
        return $this->charset;
    }

    public function getPersistent() {
        return $this->persistent;
    }

    public function getErrorMode() {
        return $this->error_mode;
    }

    public function getEmulatePrepares() {
        return $this->emulate_prepares;
    }

    public function getStringifyFetches() {
        return $this->stringify_fetches;
    }

    public function getSchema() {
        return $this->schema;
    }
    
}

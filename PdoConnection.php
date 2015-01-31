<?php

namespace YeahOrm;

/**
 * PDO connection class
 * 
 * @author David Cavar
 */
class PdoConnection extends \PDO {

    /**
     * Creates PdoConnection object
     * 
     * @param mixed $options PdoConnection options
     */
    public function __construct(\YeahOrm\DatabaseConfigInterface $config) {
        parent::__construct($config->getDsn(), $config->getUsername(), $config->getPassword(), array(
            \PDO::ATTR_PERSISTENT => $config->getPersistent(),
            \PDO::ATTR_ERRMODE => $config->getErrorMode(),
            \PDO::ATTR_EMULATE_PREPARES => $config->getEmulatePrepares(),
            \PDO::ATTR_STRINGIFY_FETCHES => $config->getStringifyFetches()
        ));
    }
}

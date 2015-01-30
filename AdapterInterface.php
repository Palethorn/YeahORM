<?php

namespace YeahOrm;

/**
 * Interface for database adapter initialization class implemetation
 * 
 * @author David Cavar
 */
interface AdapterInterface {

    /**
     * Initializes database adapter
     * 
     * @param array $options
     */
    function init($options);
}

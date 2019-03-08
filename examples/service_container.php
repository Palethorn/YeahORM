<?php
require_once __DIR__ . '/../vendor/autoload.php';

$service_manager = new \Yeah\ServiceManager();

$service_config = include __DIR__ . '/../config/services.php';

foreach($service_config as $id => $config) {
    $service_manager->register($id, $config);
}

$instance = $service_manager->run('yeah.orm.manager');

var_dump($instance);
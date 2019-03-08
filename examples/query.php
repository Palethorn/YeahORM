<?php
require_once __DIR__ . '/../vendor/autoload.php';

$service_manager = new \Yeah\ServiceManager();
$service_config = include __DIR__ . '/../config/services.php';

foreach($service_config as $id => $config) {
    $service_manager->register($id, $config);
}

$manager = $service_manager->run('yeah.orm.manager');
$connection = $manager->getConnection();

$stmt = $connection->prepare((new \Yeah\Dbal\Query())
    ->select(array('a.a', 'b.b', 'c.c'))
    ->from('test t1')
    ->innerJoin('test1', 't2', 't1.id = t2.id')
    ->andWhere('a.a = 1')
    ->getSql());

$stmt->execute();
var_dump($stmt->fetchAll());
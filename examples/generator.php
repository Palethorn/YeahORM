<?php

require_once __DIR__ . '/../vendor/autoload.php';

$conn = new Yeah\Dbal\Mysql\Connection('stg_rts', 'root', 'test');
(new Yeah\Orm\Mysql\SchemaGenerator($conn, __DIR__))->generate();
(new Yeah\Orm\Mysql\ModelGenerator(__DIR__ . '/schema', __DIR__ . '/../src/Entity'))->generate();
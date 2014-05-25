<?php
include(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');

$container = new Pimple\Container();
$container['repoFactory'] = new \Stark\core\repository\Factory();
$container['properties']  = new \Stark\core\Properties();
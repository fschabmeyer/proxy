<?php

require_once 'vendor/autoload.php';

$app = new proxy\app();
$app->setLoglevels(array('info','debug'));
$app->setConfigfile('config/config.ini');
$app->run();

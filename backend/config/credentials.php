<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
    "driver" => "mysql",
    "host" => 'localhost',
    "database" => 'tremglobal',
    "port" => '3306',
    "username" => 'root',
    "password" => '',
    "charset" => "utf8",
    "collation" => "utf8_general_ci",
    "prefix" => ""
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();

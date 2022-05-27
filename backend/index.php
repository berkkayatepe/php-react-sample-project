<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, ApiKey, X-Requested-With');

header('Access-Control-Max-Age: 86400');

error_reporting(1);

require 'vendor/autoload.php';

include 'config/credentials.php';


use Middleware\Authentication;
use PublicApi\Controller\Customer;

$app = new \Slim\App;

//$app->add(new Authentication());

$app->group('/api', function () {

    $this->post('/createCustomer', function ($request, $response, $args) {
        return Customer::create($request, $response, $args);
    });

    $this->get('/listCustomer', function ($request, $response, $args) {
        return Customer::view($request, $response, $args);
    });

});


$app->run();



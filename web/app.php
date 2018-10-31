<?php

header('Content-Type: application/json');

require __DIR__ . '/../vendor/autoload.php';

use Http\Services\Routing\Router;
use Http\Services\Request\Request;
use Http\Services\Auth\Authentication;

$config = include __DIR__ . '/../config/app.php';
Config::getInstance($config);

try {
    $request = new Request;

    $auth = new Authentication($request);
    $auth->perform();

    $router = new Router($request);
    echo $router->dispatch();
} catch (Exception $e) {
    echo json_encode(
        [
            'error' => [
                'message' => $e->getMessage(),
            ],
        ]
    );
}

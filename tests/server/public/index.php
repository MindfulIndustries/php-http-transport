<?php

require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/build_response.php');


$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

$app->router->get('/get', function () {
    return build_response(app('request'));
});

$app->run();
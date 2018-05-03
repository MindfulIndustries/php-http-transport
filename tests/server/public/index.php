<?php

require_once(__DIR__ . '/../../../vendor/autoload.php');
require_once(__DIR__ . '/build_response.php');


$app = new Laravel\Lumen\Application(
    realpath(__DIR__ . '/../')
);

foreach (['get', 'post', 'put', 'patch', 'delete'] as $method) {
    $app->router->{$method}('/' . $method, function () {
        return build_response(app('request'));
    });
}

$app->router->get('/timeout', function () {
    sleep(
        app('request')->input('seconds') ?? 2
    );
});

$app->run();
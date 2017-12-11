<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

//session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

$aimeos = new \Aimeos\Slim\Bootstrap( $app, require '../src/aimeos-settings.php' );
$aimeos->setup( '../ext' )->routes( '../src/aimeos-routes.php' );

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "realm" => "Aimeos administration",
    "path" => "/admin",
    "secure" => false,
    "users" => [
        "seid" => "seya0011",
        "admin" => "secret",
    ],
    "error" => function ($request, $response, $arguments) {
        $data = [];
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response->write(json_encode($data, JSON_UNESCAPED_SLASHES));
    }
]));

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
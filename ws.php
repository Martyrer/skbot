<?php

use Aerys\Host;
use Aerys\Request;
use Aerys\Response;
use Aerys\Router;
use function Aerys\root;

/** @var Router $router */
$router = (new Router())
    ->route("GET", "/", function(Request $request, Response $response) {
        $response->end('It Works!');
    });

$root = root(__DIR__ . "/web");

(new Host)
    ->expose("*", 5445)
    ->use($router)
    ->use($root);

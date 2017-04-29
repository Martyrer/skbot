<?php

use Aerys\{Host, Request, Response, Router, Console};
use function Aerys\root;

/** @var Router $router */
$router = (new Router())
    ->route("GET", "/", function(Request $request, Response $response) {
        $response->end('It Works!');
    });

$root = root(__DIR__ . "/web");

(new Host)
    ->expose("*", getenv('PORT'))
    ->use($router)
    ->use($root);

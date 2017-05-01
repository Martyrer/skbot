<?php

use Aerys\{Host, Request, Response, Router};
use function Aerys\root;

/** @var Router $router */
$router = (new Router())
    ->route("POST", "/", function(Request $request, Response $response) {
        $body = yield $request->getBody();
        $requestBody = json_decode($body, true);
        $token = '';

        if ($token == '') {
            $query = http_build_query(
                [
                    'grant_type' => 'client_credentials',
                    'client_id' => '014e516a-4287-4ba0-818b-0ed97a6a61e0',
                    'client_secret' => 'fOFffbhtcmWLxzuCurcTaTw',
                    'scope' => 'https://api.botframework.com/.default',
                ]
            );

            $curl = curl_init();
            curl_setopt_array(
                $curl,
                [
                    CURLOPT_URL => 'https://login.microsoftonline.com/botframework.com/oauth2/v2.0/token',
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $query,
                    CURLOPT_RETURNTRANSFER => true,
                ]
            );

            $result = curl_exec($curl);

            curl_close($curl);
            unset($curl);

            $token = json_decode($result, true)['access_token'];
        }

        $serviceUrl = $requestBody['serviceUrl'];
        $conversationId = $requestBody['conversation']['id'];

        $url = $serviceUrl . '/v3/conversations/' . $conversationId . '/activities';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'type' => 'message',
                'text' => 'Hello World!',
                'from' => [
                    'name' => 'Bot',
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                'Accept' => 'application/json, text/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
            CURLOPT_RETURNTRANSFER => true,
        ]);

        curl_exec($curl);
        curl_close($curl);
        unset($curl);

        $response->setStatus(200);
        $response->end(json_encode(['id' => 'sdfdfsddsdfwe']));
    });

$root = root(__DIR__ . "/web");

(new Host)
    ->expose("*", getenv('PORT'))
    ->use($router)
    ->use($root);

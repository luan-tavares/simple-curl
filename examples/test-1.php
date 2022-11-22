<?php

use LTL\Curl\Curl;

require_once __DIR__ .'/__init.php';

dd(
    (new Curl('https://api.hubspot.com/crm/v3/objects/contacts'))
        ->bearerToken('{{token}}')
        ->post(['properties' => ['email' => 'a@teste.com']])
);

$response = (new Curl('http://viacep.com.br/ws/01001000/json/'))->withHeaders()->get();

dd(
    $response->toObject(),
    $response->headers(),
    $response->status()
);
<?php

use LTL\Curl\Curl;

require_once __DIR__ .'/__init.php';

dump(
    (new Curl('https://api.hubspot.com/crm/v3/objects/contacts'))
        ->bearerToken(ENV['TOKEN'])
        ->post(['properties' => ['email' => 'a@teste.com']])
);

$curl = (new Curl('http://viacep.com.br/ws/01001000/json/'))->withHeaders()->get();

dd(
    $curl->toObject(),
    $curl->toArray(),
    $curl->error(),
    $curl->isMultiStatus(),
    $curl->isTooManyCurlRequestsError(),
    $curl->headers(),
    $curl->status(),
    $curl->uri()
);
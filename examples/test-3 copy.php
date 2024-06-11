<?php

use LTL\Curl\Curl;

require_once __DIR__ .'/__init.php';

$queries = [
    'limit' => 1
];

$response = (new Curl)->addUri('https://api.hubapi.com/crm/v3/objects/contacts')
    ->bearerToken(ENV['TOKEN'])
    ->withHeaders()
    ->addQueries($queries)
    ->get();

dd($response);
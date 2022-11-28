<?php

use LTL\Curl\Curl;

require_once __DIR__ .'/__init.php';

$queries = [
    'limit' => 1
];

$response = (new Curl('https://api.hubapi.com/crm/v3/objects/contacts'))
    ->bearerToken(ENV['TOKEN'])
    ->addQueries($queries)
    ->get();

dd($response);
<?php

use LTL\Curl\Curl;

require_once __DIR__ .'/__init.php';

$queries = [
    'limit' => 1
];

$response = (new Curl)->addUri('https://ifconfig.me')
    ->proxySocks5('localhost:5555')
    ->withHeaders()
    ->get();

dd($response);

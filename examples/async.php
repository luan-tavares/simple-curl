<?php

use Carbon\Carbon;
use LTL\Curl\Async\CurlAsync;
use LTL\Curl\Async\CurlMultiple;

require_once __DIR__ .'/__init.php';

$queries = [
    'limit' => 1
];

$multiple = new CurlMultiple;

$init = Carbon::now();

for($i = 0; $i < 100;$i++) {
    $curl = (new CurlAsync)->addUri('https://api.hubapi.com/crm/v3/objects/contacts')
        ->bearerToken(ENV['TOKEN'])
        ->withHeaders()
        ->addQueries($queries)
        ->get();
    $multiple->add($curl);
}

$multiple->call(function (CurlAsync $curl) {
    dump($curl->status(), $curl->response());
});

dd(Carbon::now()->diffInMilliseconds($init)/1000);

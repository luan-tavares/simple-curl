<?php

use Carbon\Carbon;
use LTL\Curl\Async\CurlAsync;
use LTL\Curl\Async\CurlMultiple;

require_once __DIR__ .'/__init.php';

$queries = [
    'limit' => 100
];

$multiple = new CurlMultiple;

$init = Carbon::now();

for($i = 0; $i < 500;$i++) {
    $curl = (new CurlAsync)->addUri('https://api.hubapi.com/crm/v3/objects/contacts')
        ->bearerToken(ENV['TOKEN'])
        ->withHeaders()
        ->addQueries($queries)
        ->get();
    $multiple->push($curl);
}

$multiple->call();

foreach ($multiple as $key => $value) {
    if($value->status() === 0) {
        dump($value->status(), $value->headers());
    }
}

while(true) {

    sleep(1);

    if(!$multiple->errors()) {
        break;
    }

    $multiple = new CurlMultiple($multiple->errors());

    $multiple->call();

    foreach ($multiple as $key => $value) {
        dump($value->status());
    }
}



dd(Carbon::now()->diffInMilliseconds($init)/1000);
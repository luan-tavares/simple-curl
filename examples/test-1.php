<?php

use LTL\Curl\Curl;

require_once __DIR__ .'/__init.php';

$curl =   (new Curl('https://desincha/test'))->setTimeout(1)->get();

//$curl = (new Curl('http://viacep.com.br/ws/01001000/json/'))->withHeaders()->get();

//dd($curl);

dd(
    $curl->toObject(),
    $curl->response(),
    $curl->toArray(),
    $curl->error(),
    $curl->isMultiStatus(),
    $curl->isTooManyCurlRequestsError(),
    $curl->headers(),
    $curl->status(),
    $curl->uri(),
    $curl->isNotFoundError(),
    $curl->isTimeoutError(),
    $curl->isConflicError()
);

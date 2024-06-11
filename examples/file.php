<?php

use LTL\Curl\Curl;

require_once __DIR__ .'/__init.php';

$url = 'https://www.wikihow.com/images/thumb/a/a9/Create-a-Link-Step-1-Version-5.jpg/aid1595728-v4-728px-Create-a-Link-Step-1-Version-5.jpg';

$curl = (new Curl($url))->saveInFile(__DIR__ .'/teste.jpg')->get();

dd($curl);

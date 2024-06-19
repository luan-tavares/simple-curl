<?php

use LTL\Curl\Curl;

require_once __DIR__ .'/__init.php';

$a = new class extends Curl {
    protected function boot(): void
    {
        $this->proxySocks5('localhost:5555');
    }
};

dd(new $a);

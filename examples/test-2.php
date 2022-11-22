<?php

use LTL\Curl\Curl;

require_once __DIR__ .'/__init.php';

$fileName = '__init.php';

$filePath = __DIR__ ."/{$fileName}";
$upload_file = new \CURLFile($filePath);

$file_options = [
    'access' => 'PRIVATE',
    'ttl' => 'P3M',
    'overwrite' => true,
    'duplicateValidationStrategy' => 'NONE',
    'duplicateValidationScope' => 'ENTIRE_PORTAL'
];

$post_data = [
    'file' => $upload_file,
    'options' => json_encode($file_options),
    'folderPath' => '/__luanteste',
    'fileName' => $fileName
];

$response = (new Curl('https://api.hubapi.com/filemanager/api/v3/files/upload'))
    ->bearerToken(ENV['TOKEN'])
    ->multipartFormData()
    ->progressBar()
    ->post($post_data);

dd($response);
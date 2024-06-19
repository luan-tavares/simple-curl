<?php

namespace LTL\Curl;

abstract class CurlConstants
{
    public const CURL_PARAMS = [
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_SSL_VERIFYPEER  => 0,
        CURLOPT_HTTPPROXYTUNNEL => false,
        CURLOPT_SSL_VERIFYHOST  => 0,
        CURLOPT_CUSTOMREQUEST   => null,
        CURLOPT_MAXREDIRS       => -1,
        CURLOPT_TIMEOUT         => 0,
        CURLOPT_HEADER          => false,
    ];

    public const DEFAULT_CONTENT_TYPE = self::CONTENT_TYPE_APPLICATION_JSON;

    public const CONTENT_TYPE_MULTIPART_FORM_DATA = 'multipart/form-data';

    public const CONTENT_TYPE_APPLICATION_JSON = 'application/json';

    public const CONTENT_TYPE_APPLICATION_FORM_URLENCODED = 'application/x-www-form-urlencoded';

    public const CONTENT_TYPE_APPLICATION_XML = 'application/xml';
    
    public const JSON_ENCODE = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE;

    public const MULTI_STATUS = 207;

    public const TOO_MANY_REQUESTS_STATUS = 429;

    public const CONFLIC_STATUS_ERROR = 409;

    public const NOT_FOUND_STATUS_ERROR = 404;

    public const TIMEOUT_STATUS_ERROR = 408;
}

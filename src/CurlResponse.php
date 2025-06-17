<?php

namespace LTL\Curl;

use CurlConnectionException;
use CurlHostResolveException;
use CurlHttp2StreamException;
use CurlPartialFileException;
use CurlRecvException;
use CurlSendException;
use CurlTimeoutException;
use CurlTLSEngineException;
use CurlTLSException;
use CurlTooManyRedirectsException;
use LTL\Curl\Async\CurlAsyncRequest;
use LTL\Curl\CurlException;
use LTL\Curl\CurlResponseBody;
use LTL\Curl\CurlResponseStatus;
use LTL\Curl\Interfaces\CurlRequestInterface;
use LTL\Curl\Interfaces\CurlResponseInterface;

class CurlResponse implements CurlResponseInterface
{
    private CurlResponseStatus $status;

    private CurlResponseBody $body;

    private CurlResponseHeader $header;


    public function __construct(CurlRequestInterface $request)
    {
        $curl = $request->curl();

        if ($request instanceof CurlAsyncRequest) {
            $rawResponse = curl_multi_getcontent($curl);
        } else {
            $rawResponse = curl_exec($curl);
        }

        if (($errorCode = curl_errno($curl)) !== 0) {
            $message = curl_error($curl);
            switch ($errorCode) {
                case CURLE_OPERATION_TIMEDOUT:
                    throw new CurlTimeoutException($message, $errorCode);
                case CURLE_RECV_ERROR:
                    throw new CurlRecvException($message, $errorCode);
                case 92:
                    throw new CurlHttp2StreamException($message, $errorCode);
                case CURLE_SSL_CONNECT_ERROR:
                case CURLE_SSL_CACERT:
                case 51:
                    throw new CurlTLSException($message, $errorCode);
                case CURLE_COULDNT_RESOLVE_HOST:
                    throw new CurlHostResolveException($message, $errorCode);
                case CURLE_COULDNT_CONNECT:
                    throw new CurlConnectionException($message, $errorCode);
                case CURLE_TOO_MANY_REDIRECTS:
                    throw new CurlTooManyRedirectsException($message, $errorCode);
                case CURLE_PARTIAL_FILE:
                    throw new CurlPartialFileException($message, $errorCode);
                case CURLE_SEND_ERROR:
                    throw new CurlSendException($message, $errorCode);
                case 58:
                    throw new CurlTLSEngineException($message, $errorCode);
                default:
                    if (str_contains($message, 'HTTP/2 stream')) {
                        throw new CurlHttp2StreamException($message, $errorCode);
                    }

                    throw new CurlException($message, $errorCode);
            }
        }

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        $this->body = $this->makeCurlResponseBody($request, $rawResponse, $headerSize);

        $this->header = $this->makeCurlResponseHeader($request, $rawResponse, $headerSize);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $this->status = new CurlResponseStatus($status);

        if ($request instanceof CurlRequest) {
            curl_close($curl);
        }
    }

    private function makeCurlResponseBody(CurlRequestInterface $request, string $rawResponse, int $headerSize): CurlResponseBody
    {
        if ($request->hasHeaders()) {
            return new CurlResponseBody(substr($rawResponse, $headerSize));
        }

        return new CurlResponseBody($rawResponse);
    }

    private function makeCurlResponseHeader(CurlRequestInterface $request, string $rawResponse, int $headerSize): CurlResponseHeader
    {
        if ($request->hasHeaders()) {
            return new CurlResponseHeader(substr($rawResponse, 0, $headerSize));
        }

        return new CurlResponseHeader;
    }

    public function get(): string
    {
        return $this->body->get();
    }

    public function toJson(): string|null
    {
        return $this->body->get();
    }

    public function toArray(): array
    {
        return $this->body->toArray();
    }

    public function toObject(): object|array|null
    {
        return $this->body->toObject();
    }

    public function headers(): array|null
    {
        return $this->header->get();
    }

    /** Status */

    public function status(): int
    {
        return $this->status->get();
    }

    public function isMultiStatus(): bool
    {
        return $this->status->isMultiStatus();
    }

    /** Errors */

    public function error(): bool
    {
        return $this->status->error();
    }

    public function isTooManyCurlRequestsError(): bool
    {
        return $this->status->isTooManyCurlRequestsError();
    }

    public function isConflicError(): bool
    {
        return $this->status->isConflicError();
    }

    public function isNotFoundError(): bool
    {
        return $this->status->isNotFoundError();
    }

    public function isTimeoutError(): bool
    {
        return $this->status->isTimeoutError();
    }
}

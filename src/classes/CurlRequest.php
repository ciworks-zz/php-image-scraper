<?php

namespace CiWorks\App;

use CiWorks\App\Interfaces\RemoteRequestInterface;
use Curl\Curl;
use RuntimeException;

class CurlRequest implements RemoteRequestInterface
{
    private $curl;

    public function __construct()
    {
        $this->curl = new Curl();
    }

    public function initalise(): Curl
    {
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);

        return $this->curl;
    }

    /**
     * @throws RuntimeException
     */
    public function get(string $url): string
    {
        $response = $this->curl->get($url);

        if ($this->curl->isError()) {
            throw new RuntimeException(
                sprintf(
                    'Server error - code: %d - message: %s', $this->curl->error_code, $this->curl->error_message
                )
            );
        } else {
            $response = $this->curl->response;
        }
        $this->curl->close();
        return $response;
    }

    public function setVerbosity(bool $flag): void
    {
        $this->curl->setVerbose($flag);
    }

    public function getRequestHeaders(): array
    {
        return $this->curl->request_headers;
    }

    public function getResponseHeaders(): array
    {
        return $this->curl->response_headers;
    }
}

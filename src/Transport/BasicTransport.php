<?php


namespace KTL\Sigma\Transport;


use Curl\Curl;

class BasicTransport extends Transport
{
    public function __construct(string $entrypoint, $username, $password)
    {
        parent::__construct($entrypoint, new Curl());
    }
}

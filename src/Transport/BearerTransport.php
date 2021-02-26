<?php


namespace KTL\Sigma\Transport;


use Curl\Curl;

class BearerTransport extends Transport
{
    protected $token;

    public function __construct(string $entrypoint, $username, $password)
    {
        parent::__construct($entrypoint, new Curl());
    }
}

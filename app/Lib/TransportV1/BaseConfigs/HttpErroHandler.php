<?php


namespace App\Lib\Sigma\Standard;

use Exception;
use Throwable;

class APIException extends Exception
{
    protected $response;

    public function __construct($response, $message = "", $httpCode = 0, Throwable $previous = null)
    {
        parent::__construct("The request returned $httpCode : $message", $httpCode, $previous);
    }

    /**
     * @param null $key
     * @return mixed
     */
    public function getResponse($key = null)
    {
        return $key ? $this->response[$key] : $this->response;
    }

    public function getApiMessage()
    {
        return $this->getResponse('message');
    }

    public function getApiErrors()
    {
        return $this->getResponse('errors');
    }

}

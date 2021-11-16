<?php

namespace App\Lib\Sigma\BaseConfigs;

use BaseConnector;
use App\Lib\Transport\Exceptions\ApiException;
use App\Lib\Transport\Exceptions\Handlers\AbstractErrorHandler;
use App\Lib\Transport\Exceptions\Handlers\BadRequestErrorHandler;
use App\Lib\Transport\Exceptions\Handlers\ForbiddenErrorHandler;
use App\Lib\Transport\Exceptions\Handlers\NetworkErrorHandler;
use App\Lib\Transport\Exceptions\Handlers\NotFoundErrorHandler;
use App\Lib\Transport\Exceptions\Handlers\UnauthorizedErrorHandler;
use Curl\Curl;

class NTLMTransporter extends BaseConnector
{
    const HTTP_NETWORK_ERROR_CODE = 0;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND_ERROR_CODE = 404;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    const  JSON_MIME_TYPE = 'application/json';

    protected $endpoint;

    protected $client;

    protected $errorHandlers = [];

    protected $apiDirector = [];

    public function __construct(string $endpoint, Curl $client)
    {
        $this->client = $client;
        $this->endpoint = rtrim($endpoint, '/').'/';

        $this->setErrorHandler(self::HTTP_NETWORK_ERROR_CODE, new NetworkErrorHandler($this));
        $this->setErrorHandler(self::HTTP_UNAUTHORIZED, new UnauthorizedErrorHandler($this));
        $this->setErrorHandler(self::HTTP_FORBIDDEN, new ForbiddenErrorHandler($this));
        $this->setErrorHandler(self::HTTP_NOT_FOUND_ERROR_CODE, new NotFoundErrorHandler($this));
        $this->setErrorHandler(self::HTTP_BAD_REQUEST, new BadRequestErrorHandler($this));
        $this->setErrorHandler(self::HTTP_UNPROCESSABLE_ENTITY, new BadRequestErrorHandler($this));    }

    public function setAPIDirector ($ConnectorPrefix)
    {

    }
    public function setErrorHandler(int $code, ?AbstractErrorHandler $handler)
    {
        $this->errorHandlers[$code] = $handler;

        if (is_null($handler)){
            unset($this->errorHandlers[$code]);
        }

        return $this;
    }

    public function request($endpoint, array $data = [], $method = 'get')
    {
        try {
            $rawResponse = $this->rawRequest($endpoint, $data, $method);
            $httpStatusCode = $this->getClient()->getInfo(CURLINFO_HTTP_CODE);
            $response = json_decode($rawResponse, true);

            if ($httpStatusCode >= 200 && $httpStatusCode <= 299){
                return $response;
            }

            $exception = new ApiException(
                $response,
                $response['message'] ?? $rawResponse ?? 'Unknown error message',
                $httpStatusCode
            );

            if ($handler = $this->errorHandlers[$httpStatusCode] ?? false){
                return $handler->handle($exception, compact('endpoint', 'data', 'method'));
            }

            throw $exception;
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public function rawRequest($endpoint, array $data = [], $method = 'get')
    {
        try {
            $method = strtolower($method);
            switch ($method){
                case 'get':
                    $this->getClient()->get($this->getUrl($endpoint, $data));
                    break;
                case 'post':
                    $this->getClient()->post($this->getUrl($endpoint), $this->encodeBody($data), true);
                    break;
                case 'patch':
                    $this->getClient()->setHeader('if-Match', '*');
                    $this->getClient()->patch($this->getUrl($endpoint), $this->encodeBody($data), true);
                    break;
                case 'put':
                    $this->getClient()->put($this->getUrl($endpoint), $this->encodeBody($data));
                    break;
                case 'delete':
                    $this->getClient()->setHeader('if-Match', '*');
                    $this->getClient()->delete($this->getUrl($endpoint), $this->encodeBody($data));
                    break;
            }
            return $this->getClient()->rawResponse;
        }catch (\Exception $exception){
            throw $exception;
        }
    }
    public function file($endpoint, array $data = [], $method = 'get')
    {
        try {
            $this->getClient()->setOpt(CURLOPT_FOLLOWLOCATION, true);
            $this->getClient()->setOpt(CURLOPT_RETURNTRANSFER, true);
            $rawResponse = $this->rawRequest($endpoint, $data, $method);
            $httpStatusCode = $this->getClient()->getInfo(CURLINFO_HTTP_CODE);

            if ($httpStatusCode >= 200 && $httpStatusCode <= 299){
                return $rawResponse;
            }

            $exception = new ApiException(
                $rawResponse,
                $response['message'] ?? $rawResponse ?? 'Unknown error message',
                $httpStatusCode
            );

            if ($handler = $this->errorHandlers[$httpStatusCode] ?? false){
                return $handler->handle($exception, compact('endpoint', 'data', 'method'));
            }

            throw $exception;
        }catch (\Exception $exception){
            throw $exception;
        }
    }
    /**
     * @return Curl
     */
    public function getClient(): Curl
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getUrl(string $endpoint, array $data = [])
    {
        $url = $this->getEndpoint(). ltrim($endpoint, '/');

        return $url . $this->appendData($data);
    }

    public function appendData(array $data = []){
        if (!count($data)){
            return null;
        }

        $data = array_map(function ($item){
            return is_null($item) ? '' : $item;
        }, $data);

        return '?' . http_build_query($data);
    }

    public function encodeBody($data)
    {
        $this->getClient()->setHeader('Content-Type', static::JSON_MIME_TYPE);
        return json_encode($data);
    }

}

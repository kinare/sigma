<?php


namespace KTL\Sigma\Transport;


use KTL\Sigma\Transport\Contract\TransportInterface;
use KTL\Sigma\Transport\Exceptions\ApiException;
use KTL\Sigma\Transport\Exceptions\Handlers\AbstractErrorHandler;
use KTL\Sigma\Transport\Exceptions\Handlers\BadRequestErrorHandler;
use KTL\Sigma\Transport\Exceptions\Handlers\ForbiddenErrorHandler;
use KTL\Sigma\Transport\Exceptions\Handlers\NetworkErrorHandler;
use KTL\Sigma\Transport\Exceptions\Handlers\NotFoundErrorHandler;
use KTL\Sigma\Transport\Exceptions\Handlers\UnauthorizedErrorHandler;
use Curl\Curl;
use Illuminate\Support\Facades\Log;

class Transport implements TransportInterface
{
    const HTTP_NETWORK_ERROR_CODE = 0;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND_ERROR_CODE = 404;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    const  JSON_MIME_TYPE = 'application/json';

    protected $entrypoint;

    protected $client;

    protected $errorHandlers = [];

    public function __construct(string $entrypoint, Curl $client)
    {
        $this->client = $client;
        $this->entrypoint = rtrim($entrypoint, '/').'/';

        $this->setErrorHandler(self::HTTP_NETWORK_ERROR_CODE, new NetworkErrorHandler($this));
        $this->setErrorHandler(self::HTTP_UNAUTHORIZED, new UnauthorizedErrorHandler($this));
        $this->setErrorHandler(self::HTTP_FORBIDDEN, new ForbiddenErrorHandler($this));
        $this->setErrorHandler(self::HTTP_NOT_FOUND_ERROR_CODE, new NotFoundErrorHandler($this));
        $this->setErrorHandler(self::HTTP_BAD_REQUEST, new BadRequestErrorHandler($this));
        $this->setErrorHandler(self::HTTP_UNPROCESSABLE_ENTITY, new BadRequestErrorHandler($this));    }

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
        $rawResponse = $this->rawRequest($endpoint, $data, $method);
        $httpStatusCode = $this->getClient()->getInfo(CURLINFO_HTTP_CODE);
        $response = json_decode($rawResponse, true);

        if ($httpStatusCode >= 200 && $httpStatusCode <= 299){
            return $response;
        }

        $exception = new ApiException($response, $response['message'] ?? $rawResponse ?? 'Unknown error message', $httpStatusCode);

        /* Log Api Errors */
        Log::error($exception->getMessage());

        if ($handler = $this->errorHandlers[$httpStatusCode] ?? false){
            return $handler->handle($exception, compact('endpoint', 'data', 'method'));
        }

        throw $exception;
    }

    public function rawRequest($endpoint, array $data = [], $method = 'get')
    {
        $method = strtolower($method);
        switch ($method){
            case 'get':
                $this->getClient()->get($this->getUrl($endpoint, $data));
                $rawResponse = $this->getClient()->rawResponse;

                break;
            case 'post':
                $this->getClient()->post($this->getUrl($endpoint), $this->encodeBody($data), true);
                break;
            case 'put':
                $this->getClient()->put($this->getUrl($endpoint), $this->encodeBody($data));
                break;
            case 'delete':
                $this->getClient()->delete($this->getUrl($endpoint), $this->encodeBody($data));
                break;
        }
        return $this->getClient()->rawResponse;

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
    public function getEntrypoint(): string
    {
        return $this->entrypoint;
    }

    public function getUrl(string $endpoint, array $data = [])
    {
        $url = $this->getEntrypoint(). ltrim($endpoint, '/');

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

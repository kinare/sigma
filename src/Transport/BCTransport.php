<?php


namespace KTL\Sigma\Transport;

use Curl\Curl;
use Exception;
use KTL\Sigma\Transport\Exceptions\ApiException;

class BCTransport extends Transport
{
    /**
     * BCTransport constructor.
     * @param $baseApi
     * @param $username
     * @param $password
     */
    public function __construct($baseApi, $username, $password)
    {
        parent::__construct($baseApi, new Curl());
        $this->getClient()->setOpt(CURLOPT_USERPWD, $username.':'.$password);
        $this->getClient()->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    }

    /**
     * @param $endpoint
     * @param array $data
     * @param string $method
     * @return array|array[]
     * @throws ApiException
     */
    public function request($endpoint, array $data = [], $method = 'get'): array
    {
        $res = parent::request($endpoint, $data, $method);
        if (is_array($res))
            if (key_exists('value', $res))
                return $res['value'];
        return [$res];
    }

    /**
     * @param $endpoint
     * @param array $data
     * @param string $method
     * @return mixed|null
     */
    public function rawRequest($endpoint, array $data = [], $method = 'get')
    {
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
                $this->getClient()->patch($this->getUrl($endpoint), $this->encodeBody($data));
                break;
            case 'delete':
                $this->getClient()->setHeader('if-Match', '*');
                $this->getClient()->delete($this->getUrl($endpoint), $this->encodeBody($data));
                break;
        }
        return $this->getClient()->rawResponse;
    }

    /**
     * @param $endpoint
     * @param array $data
     * @param string $method
     * @return mixed|null
     * @throws Exception
     */
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
        }catch (Exception $exception){
            throw $exception;
        }
    }

    /**
     * @param $endpoint
     * @param array $data
     * @param null $company
     * @param string $method
     * @return \array[][]
     * @throws ApiException
     */
    public function cu($endpoint, array $data = [], $company = null, $method = 'post'): array
    {
        try {
            $endpoint .=  $this->appendData(['company' => $company]);
            $res = parent::request($endpoint, $data , $method);

            if (is_array($res))
                if (key_exists('value', $res))
                    return [$res['value']];

            return [$res];
        }catch (Exception $exception){
            throw $exception;
        }
    }
}

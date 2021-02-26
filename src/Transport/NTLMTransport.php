<?php


namespace KTL\Sigma\Transport;

use Curl\Curl;

class NTLMTransport extends Transport
{
    public function __construct($baseApi, $username, $password)
    {
        parent::__construct($baseApi, new Curl());
        $this->getClient()->setOpt(CURLOPT_USERPWD, $username.':'.$password);
        $this->getClient()->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
    }

    public function request($endpoint, array $data = [], $method = 'get')
    {
        $res = parent::request($endpoint, $data, $method);
        if (is_array($res))
            if (key_exists('value', $res))
                return $res['value'];
        return [$res];
    }

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
}

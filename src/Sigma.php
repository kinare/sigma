<?php

namespace KTL\Sigma;

use Exception;
use KTL\Sigma\Models\SigmaProvider;
use KTL\Sigma\Models\SigmaWrapper;
use KTL\Sigma\Transport\BasicTransport;
use KTL\Sigma\Transport\BearerTransport;
use KTL\Sigma\Transport\BCTransport;
use KTL\Sigma\Transport\Transport;
use KTL\Sigma\Wrapper\Wrapper;

class Sigma
{
    public $sigmaProvider;
    public $sigmaWrapper;
    public $transport;

    /**
     * @param $provider
     * @param $entity
     * @param array $payload
     * @param string $method
     * @return array|null
     * @throws Exception
     */
    public function request($provider, $entity, $payload = [], $method = 'get'): ?array
    {
        try {
            $this->sigmaProvider = SigmaProvider::where('Provider', mb_strtoupper($provider))->first();
            $this->sigmaWrapper = SigmaWrapper::where([
                'upStreamID' => $entity,
                'provider' => $this->sigmaProvider->Provider
            ])->first();
            $wrapper = new Wrapper($this->sigmaWrapper, $payload, $method);
            $wrapper->validateOperation();
            return $wrapper->execute($this->getTransport($provider), $this->sigmaProvider->company);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $provider
     * @return Transport
     */
    public function getTransport($provider): Transport
    {
        switch ($this->sigmaProvider->authType){
            case "NTLM":
                return new BCTransport(
                    $this->sigmaProvider->baseConnectionPath,
                    $this->sigmaProvider->userName,
                    $this->sigmaProvider->password
                );

            case 'BEARER' :
                return new BearerTransport(
                    $this->sigmaProvider->baseConnectionPath,
                    $this->sigmaProvider->userName,
                    $this->sigmaProvider->password
                );

            case 'BASIC' :
                return new BasicTransport(
                    $this->sigmaProvider->baseConnectionPath,
                    $this->sigmaProvider->userName,
                    $this->sigmaProvider->password
                );
        }
    }
}

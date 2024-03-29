<?php

namespace KTL\Sigma\Transport\Exceptions\Handlers;


use KTL\Sigma\Transport\Exceptions\ApiException;

/**
 * Class NetworkErrorHandler
 * @package Cristal\ApiWrapper\Exceptions
 */
class NetworkErrorHandler extends AbstractErrorHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(ApiException $exception, array $requestArguments)
    {
        if ($this->tries < $this->getMaxTries()) {
            $this->tries++;

            $return = $this->transport->request(
                $requestArguments['endpoint'],
                $requestArguments['data'],
                $requestArguments['method']
            );

            $this->tries = 0;
            return $return;
        }

        $this->tries = 0;

        throw $exception;
    }
}

<?php

namespace KTL\Sigma\Transport\Exceptions\Handlers;


use KTL\Sigma\Transport\Exceptions\ApiException;
use KTL\Sigma\Transport\Exceptions\ApiUnauthorizedException;

/**
 * Class UnauthorizedErrorHandler
 * @package Cristal\ApiWrapper\Exceptions
 */
class UnauthorizedErrorHandler extends AbstractErrorHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(ApiException $exception, array $requestArguments)
    {
        throw new ApiUnauthorizedException(
            $exception->getResponse(),
            $exception->getCode(),
            $exception->getPrevious()
        );
    }
}

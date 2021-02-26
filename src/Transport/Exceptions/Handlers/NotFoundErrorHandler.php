<?php

namespace KTL\Sigma\Transport\Exceptions\Handlers;


use KTL\Sigma\Transport\Exceptions\ApiEntityNotFoundException;
use KTL\Sigma\Transport\Exceptions\ApiException;

/**
 * Class NetworkErrorHandler
 * @package Cristal\ApiWrapper\Exceptions
 */
class NotFoundErrorHandler extends AbstractErrorHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(ApiException $exception, array $requestArguments)
    {
        throw new ApiEntityNotFoundException(
            $exception->getResponse(),
            $exception->getCode(),
            $exception->getPrevious()
        );
    }
}

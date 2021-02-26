<?php

namespace KTL\Sigma\Transport\Exceptions\Handlers;


use KTL\Sigma\Transport\Exceptions\ApiBadRequestException;
use KTL\Sigma\Transport\Exceptions\ApiException;

/**
 * Class BadRequestErrorHandler
 * @package Cristal\ApiWrapper\Exceptions
 */
class BadRequestErrorHandler extends AbstractErrorHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(ApiException $exception, array $requestArguments)
    {
        throw new ApiBadRequestException(
            $exception->getResponse(),
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getPrevious()
        );
    }
}

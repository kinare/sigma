<?php

namespace KTL\Sigma\Transport\Exceptions\Handlers;


use KTL\Sigma\Transport\Exceptions\ApiException;
use KTL\Sigma\Transport\Exceptions\ApiForbiddenException;

/**
 * Class ForbiddenErrorHandler
 * @package Cristal\ApiWrapper\Exceptions
 */
class ForbiddenErrorHandler extends AbstractErrorHandler
{
    /**
     * {@inheritdoc}
     */
    public function handle(ApiException $exception, array $requestArguments)
    {
        throw new ApiForbiddenException(
            $exception->getResponse(),
            $exception->getCode(),
            $exception->getPrevious()
        );
    }
}

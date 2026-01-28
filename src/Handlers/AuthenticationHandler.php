<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Handlers;

use Illuminate\Auth\AuthenticationException;
use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;
use Throwable;

class AuthenticationHandler extends AbstractHandler
{
    /**
     * @var AuthenticationException
     */
    protected Throwable $exception;

    public function __construct(AuthenticationException $e)
    {
        parent::__construct($e);
    }

    public function handle(): ErrorHandledInterface|ErrorHandledCollectionInterface
    {
        return (new Error)->setStatus('401')
            ->setCode((string) $this->getCode('authentication'))
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->getDefaultTitle())
            ->setDetail((string) __('exception::exceptions.authentication.detail'));
    }
}

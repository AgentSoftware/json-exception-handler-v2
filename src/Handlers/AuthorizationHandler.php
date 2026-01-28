<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Handlers;

use Illuminate\Auth\Access\AuthorizationException;
use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;
use Throwable;

class AuthorizationHandler extends AbstractHandler
{
    /**
     * @var AuthorizationException
     */
    protected Throwable $exception;

    public function __construct(AuthorizationException $e)
    {
        parent::__construct($e);
    }

    public function handle(): ErrorHandledInterface|ErrorHandledCollectionInterface
    {
        return (new Error)->setStatus('403')
            ->setCode((string) $this->getCode('authorization'))
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle((string) __('exception::exceptions.authorization.title'))
            ->setDetail($this->exception->getMessage());
    }
}

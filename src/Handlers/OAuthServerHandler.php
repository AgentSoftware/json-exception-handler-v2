<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Handlers;

use League\OAuth2\Server\Exception\OAuthServerException;
use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;
use Throwable;

class OAuthServerHandler extends AbstractHandler
{
    /**
     * @var OAuthServerException
     */
    protected Throwable $exception;

    /**
     * Create instance using the Exception to be handled.
     */
    public function __construct(OAuthServerException $e)
    {
        parent::__construct($e);
    }

    public function handle(): ErrorHandledInterface|ErrorHandledCollectionInterface
    {
        return (new Error)->setStatus((string) $this->getStatusCode())
            ->setCode((string) $this->getCode('oauth_server'))
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->exception->getErrorType())
            ->setDetail($this->exception->getMessage());
    }
}

<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Handlers;

use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;

class Handler extends AbstractHandler
{
    public function handle(): ErrorHandledInterface|ErrorHandledCollectionInterface
    {
        return (new Error)->setStatus((string) $this->getStatusCode())
            ->setCode((string) $this->getCode())
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->getDefaultTitle())
            ->setDetail($this->exception->getMessage());
    }
}

<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Handlers;

use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

class BadRequestHttpHandler extends AbstractHandler
{
    /**
     * @var BadRequestHttpException
     */
    protected Throwable $exception;

    public function __construct(BadRequestHttpException $e)
    {
        parent::__construct($e);
    }

    public function handle(): ErrorHandledInterface|ErrorHandledCollectionInterface
    {
        return (new Error)->setStatus('400')
            ->setCode((string) $this->getCode('bad_request'))
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->getDefaultTitle())
            ->setDetail($this->exception->getMessage());
    }
}

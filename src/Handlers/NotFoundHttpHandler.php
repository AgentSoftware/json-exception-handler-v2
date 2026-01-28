<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Handlers;

use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class NotFoundHttpHandler extends AbstractHandler
{
    /**
     * @var NotFoundHttpException
     */
    protected Throwable $exception;

    public function __construct(NotFoundHttpException $e)
    {
        parent::__construct($e);
    }

    public function handle(): ErrorHandledInterface|ErrorHandledCollectionInterface
    {
        return (new Error)->setStatus((string) $this->getStatusCode())
            ->setCode((string) $this->getCode('not_found_http'))
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->getDefaultTitle())
            ->setDetail($this->getNotFoundMessage());
    }

    /**
     * Get message based on file. If file is RouteCollection return specific message.
     */
    public function getNotFoundMessage(): string
    {
        $message = ! empty($this->exception->getMessage())
            ? $this->exception->getMessage()
            : class_basename($this->exception);

        if (basename($this->exception->getFile()) === 'RouteCollection.php') {
            $message = (string) __('exception::exceptions.not_found_http.message');
        }

        return $message;
    }
}

<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Response;

use Illuminate\Http\JsonResponse;

abstract class AbstractResponse
{
    /**
     * The HTTP status code.
     */
    protected int $status;

    /**
     * The errors on response.
     */
    protected ErrorHandledCollectionInterface $errors;

    /**
     * Create new Response response passing the errors.
     */
    public function __construct(ErrorHandledCollectionInterface $errors)
    {
        $this->errors = $errors;

        $this->setStatus((int) $this->errors->getStatusCode());
    }

    /**
     * Get the HTTP status code.
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Set the HTTP status code.
     */
    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the errors on response.
     */
    public function getErrors(): ErrorHandledCollectionInterface
    {
        return $this->errors;
    }

    /**
     * Returns JSON response.
     */
    abstract public function json(): JsonResponse;
}

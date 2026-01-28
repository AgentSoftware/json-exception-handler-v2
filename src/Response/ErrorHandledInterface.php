<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Response;

interface ErrorHandledInterface
{
    /**
     * Adds itself to a error collection and returns the instance of collection.
     */
    public function toCollection(): ErrorHandledCollectionInterface;

    /**
     * Get the HTTP status code applicable to this problem, expressed as a string value.
     */
    public function getStatus(): string;
}

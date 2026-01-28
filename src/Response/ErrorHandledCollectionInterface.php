<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Response;

interface ErrorHandledCollectionInterface extends ErrorCollectionInterface
{
    /**
     * Check if content on collection contains only the type class passed on
     * $type parameter. If one item on collection was different of type an
     * exception is thrown.
     *
     * @param  class-string  $type
     *
     * @throws InvalidContentException
     */
    public function validatedContent(string $type): self;
}

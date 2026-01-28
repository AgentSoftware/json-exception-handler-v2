<?php

declare(strict_types=1);

namespace SMartins\Exceptions\JsonApi;

use Illuminate\Contracts\Support\Arrayable;
use SMartins\Exceptions\Traits\NotNullArrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class Source implements Arrayable
{
    use NotNullArrayable;

    /**
     * A JSON Pointer [RFC6901] to the associated entity in the request document
     * [e.g. "/data" for a primary data object, or "/data/attributes/title" for
     * a specific attribute].
     */
    protected ?string $pointer = null;

    /**
     * A string indicating which URI query parameter caused the error.
     */
    protected ?string $parameter = null;

    /**
     * Get pointer.
     */
    public function getPointer(): ?string
    {
        return $this->pointer;
    }

    /**
     * Set pointer.
     */
    public function setPointer(string $pointer): static
    {
        $this->pointer = $pointer;

        return $this;
    }

    /**
     * Get parameter.
     */
    public function getParameter(): ?string
    {
        return $this->parameter;
    }

    /**
     * Set parameter.
     */
    public function setParameter(string $parameter): static
    {
        $this->parameter = $parameter;

        return $this;
    }
}

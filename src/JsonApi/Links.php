<?php

declare(strict_types=1);

namespace SMartins\Exceptions\JsonApi;

use Illuminate\Contracts\Support\Arrayable;
use SMartins\Exceptions\Traits\NotNullArrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class Links implements Arrayable
{
    use NotNullArrayable;

    /**
     * A link that leads to further details about this particular occurrence of
     * the problem.
     */
    protected ?string $about = null;

    /**
     * Get the about link.
     */
    public function getAbout(): ?string
    {
        return $this->about;
    }

    /**
     * Set the about link.
     */
    public function setAbout(string $about): static
    {
        $this->about = $about;

        return $this;
    }
}

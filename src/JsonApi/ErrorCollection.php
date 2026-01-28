<?php

declare(strict_types=1);

namespace SMartins\Exceptions\JsonApi;

use Illuminate\Support\Collection;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\InvalidContentException;

/**
 * @extends Collection<int, Error>
 */
class ErrorCollection extends Collection implements ErrorHandledCollectionInterface
{
    /**
     * The HTTP status code applicable to this problem, expressed as a string value.
     */
    protected ?string $statusCode = null;

    /**
     * The HTTP headers on response.
     *
     * @var array<string, mixed>
     */
    protected array $headers = [];

    /**
     * Returns the status code.
     */
    public function getStatusCode(): ?string
    {
        return $this->statusCode;
    }

    /**
     * Returns response headers.
     *
     * @return array<string, mixed>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the status code.
     */
    public function setStatusCode(string $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Set the headers of response.
     *
     * @param  array<string, mixed>  $headers
     */
    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @throws InvalidContentException
     */
    public function validatedContent(string $type): ErrorHandledCollectionInterface
    {
        foreach ($this->items as $item) {
            if (! $item instanceof $type) {
                throw new InvalidContentException('All items on ['.self::class.'] must to be instances of ['.$type.'].');
            }
        }

        return $this;
    }
}

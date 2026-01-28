<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Response;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @extends Arrayable<int, mixed>
 */
interface ErrorCollectionInterface extends Arrayable
{
    /**
     * Returns response headers.
     *
     * @return array<string, mixed>
     */
    public function getHeaders(): array;

    /**
     * Set HTTP status code of response.
     */
    public function setStatusCode(string $statusCode): static;

    /**
     * Get the HTTP status code.
     */
    public function getStatusCode(): ?string;
}

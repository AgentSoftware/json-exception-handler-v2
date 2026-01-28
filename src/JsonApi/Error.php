<?php

declare(strict_types=1);

namespace SMartins\Exceptions\JsonApi;

use Illuminate\Contracts\Support\Arrayable;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;
use SMartins\Exceptions\Traits\NotNullArrayable;

/**
 * @implements Arrayable<string, mixed>
 */
class Error implements Arrayable, ErrorHandledInterface
{
    use NotNullArrayable;

    /**
     * A unique identifier for this particular occurrence of the problem.
     */
    protected ?string $id = null;

    protected ?Links $links = null;

    /**
     * The HTTP status code applicable to this problem, expressed as a string value.
     */
    protected ?string $status = null;

    /**
     * An application-specific error code, expressed as a string value.
     */
    protected ?string $code = null;

    /**
     * A short, human-readable summary of the problem that SHOULD NOT change from
     * occurrence to occurrence of the problem, except for purposes of localization.
     */
    protected ?string $title = null;

    /**
     * A human-readable explanation specific to this occurrence of the problem.
     * Like title, this field's value can be localized.
     */
    protected ?string $detail = null;

    /**
     * An object containing references to the source of the error.
     */
    protected ?Source $source = null;

    /**
     * Get a unique identifier for this particular occurrence of the problem.
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set a unique identifier for this particular occurrence of the problem.
     */
    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of links.
     */
    public function getLinks(): ?Links
    {
        return $this->links;
    }

    /**
     * Set the value of links.
     */
    public function setLinks(Links $links): static
    {
        $this->links = $links;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status ?? '';
    }

    /**
     * Set the HTTP status code applicable to this problem, expressed as a string value.
     */
    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get an application-specific error code, expressed as a string value.
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Set an application-specific error code, expressed as a string value.
     */
    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get occurrence to occurrence of the problem, except for purposes of localization.
     */
    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    /**
     * Set occurrence to occurrence of the problem, except for purposes of localization.
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get like title, this field's value can be localized.
     */
    public function getDetail(): ?string
    {
        return $this->detail;
    }

    /**
     * Set like title, this field's value can be localized.
     */
    public function setDetail(string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get an object containing references to the source of the error.
     */
    public function getSource(): ?Source
    {
        return $this->source;
    }

    /**
     * Set an object containing references to the source of the error.
     */
    public function setSource(Source $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function toCollection(): ErrorHandledCollectionInterface
    {
        return new ErrorCollection([$this]);
    }
}

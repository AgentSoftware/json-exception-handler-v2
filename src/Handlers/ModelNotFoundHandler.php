<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Handlers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;
use Throwable;

class ModelNotFoundHandler extends AbstractHandler
{
    /**
     * @var ModelNotFoundException
     */
    protected Throwable $exception;

    /**
     * Create instance using the Exception to be handled.
     */
    public function __construct(ModelNotFoundException $e)
    {
        parent::__construct($e);
    }

    public function handle(): ErrorHandledInterface|ErrorHandledCollectionInterface
    {
        $entity = $this->extractEntityName($this->exception->getModel());

        $detail = __('exception::exceptions.model_not_found.title', ['model' => $entity]);

        return (new Error)->setStatus('404')
            ->setCode((string) $this->getCode('model_not_found'))
            ->setSource((new Source())->setPointer('data/id'))
            ->setTitle(Str::snake(class_basename($this->exception)))
            ->setDetail((string) $detail);
    }

    /**
     * Get entity name based on model path to mount the message.
     */
    public function extractEntityName(?string $model): string
    {
        if ($model === null) {
            return 'Model';
        }

        $classNames = explode('\\', $model);

        $entityName = end($classNames);

        if ($this->entityHasTranslation($entityName)) {
            return (string) __('exception::exceptions.models.'.$entityName);
        }

        return $entityName;
    }

    /**
     * Check if entity returned on ModelNotFoundException has translation on
     * exceptions file.
     */
    public function entityHasTranslation(string $entityName): bool
    {
        return in_array($entityName, $this->translationModelKeys(), true);
    }

    /**
     * Get the models keys on exceptions lang file.
     *
     * @return array<int, string>
     */
    private function translationModelKeys(): array
    {
        $models = __('exception::exceptions.models');

        return is_array($models) ? array_keys($models) : [];
    }
}

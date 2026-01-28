<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Handlers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use SMartins\Exceptions\JsonApi\Response as JsonApiResponse;
use SMartins\Exceptions\Response\AbstractResponse;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

abstract class AbstractHandler
{
    /**
     * The exception thrown.
     */
    protected Throwable $exception;

    /**
     * An array where the key is the class exception and the value is the handler
     * class that will treat the exception.
     *
     * @var array<class-string, class-string>
     */
    protected array $exceptionHandlers = [];

    /**
     * An internal array where the key is the exception class and the value is
     * the handler class that will treat the exception.
     *
     * @var array<class-string, class-string>
     */
    protected array $internalExceptionHandlers = [
        Throwable::class => Handler::class,
        ModelNotFoundException::class => ModelNotFoundHandler::class,
        AuthenticationException::class => AuthenticationHandler::class,
        ValidationException::class => ValidationHandler::class,
        BadRequestHttpException::class => BadRequestHttpHandler::class,
        AuthorizationException::class => AuthorizationHandler::class,
        NotFoundHttpException::class => NotFoundHttpHandler::class,
        'Laravel\Passport\Exceptions\MissingScopeException' => MissingScopeHandler::class,
        'League\OAuth2\Server\Exception\OAuthServerException' => OAuthServerHandler::class,
    ];

    /**
     * Create instance using the Throwable to be handled.
     */
    public function __construct(Throwable $e)
    {
        $this->exception = $e;
    }

    /**
     * Handle with an exception according to specific definitions. Returns one
     * or more errors using the exception from $exceptions attribute.
     */
    abstract public function handle(): ErrorHandledInterface|ErrorHandledCollectionInterface;

    /**
     * Get error code. If code is empty from config file based on type.
     */
    public function getCode(string $type = 'default'): int|string
    {
        $code = $this->exception->getCode();
        if (empty($code)) {
            return config('json-exception-handler.codes.'.$type);
        }

        return $code;
    }

    /**
     * Return response with handled exception.
     *
     * @throws \SMartins\Exceptions\Response\InvalidContentException
     */
    public function handleException(): AbstractResponse
    {
        $handler = $this->getExceptionHandler();

        $errors = $this->validatedHandledException($handler->handle());

        $responseHandler = $this->getResponseHandler();

        return new $responseHandler($errors);
    }

    /**
     * Validate response from handle method of handler class.
     *
     * @throws \SMartins\Exceptions\Response\InvalidContentException
     */
    public function validatedHandledException(ErrorHandledInterface|ErrorHandledCollectionInterface $error): ErrorHandledCollectionInterface
    {
        if ($error instanceof ErrorHandledCollectionInterface) {
            return $error->validatedContent(ErrorHandledInterface::class);
        }

        return $error->toCollection()->setStatusCode($error->getStatus());
    }

    /**
     * Get the class the will handle the Exception from exceptionHandlers attributes.
     */
    public function getExceptionHandler(): self
    {
        $handlers = $this->getConfiguredHandlers();

        $handler = $handlers[get_class($this->exception)] ?? $this->getDefaultHandler();

        if (is_string($handler)) {
            return new $handler($this->exception);
        }

        return $handler;
    }

    /**
     * Get exception handlers from internal and set on App\Exceptions\Handler.php.
     *
     * @return array<class-string, class-string>
     */
    public function getConfiguredHandlers(): array
    {
        return array_merge($this->internalExceptionHandlers, $this->exceptionHandlers);
    }

    /**
     * Get default pointer using file and line of exception.
     */
    public function getDefaultPointer(): string
    {
        return '';
    }

    /**
     * Get default title from exception.
     */
    public function getDefaultTitle(): string
    {
        return Str::snake(class_basename($this->exception));
    }

    /**
     * Get default http code. Check if exception has getStatusCode() methods.
     * If not get from config file.
     */
    public function getStatusCode(): int
    {
        if (method_exists($this->exception, 'getStatusCode')) {
            return $this->exception->getStatusCode();
        }

        return config('json-exception-handler.http_code');
    }

    /**
     * The default handler to handle not treated exceptions.
     */
    public function getDefaultHandler(): self
    {
        return new Handler($this->exception);
    }

    /**
     * Get default response handler of the if any response handler was defined
     * on config file.
     *
     * @return class-string<AbstractResponse>
     */
    public function getDefaultResponseHandler(): string
    {
        return JsonApiResponse::class;
    }

    /**
     * Get the response class that will handle the json response.
     *
     * @return class-string<AbstractResponse>
     */
    public function getResponseHandler(): string
    {
        $response = config('json-exception-handler.response_handler');

        return $response ?? $this->getDefaultResponseHandler();
    }

    /**
     * Set exception handlers.
     *
     * @param  array<class-string, class-string>  $handlers
     */
    public function setExceptionHandlers(array $handlers): static
    {
        $this->exceptionHandlers = $handlers;

        return $this;
    }
}

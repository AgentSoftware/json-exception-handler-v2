# Laravel Json Exception Handler

[![StyleCI](https://styleci.io/repos/101529653/shield?style=plastic&branch=3.0)](https://styleci.io/repos/101529653?style=plastic&branch=3.0)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sfelix-martins/json-exception-handler/badges/quality-score.png?b=3.0)](https://scrutinizer-ci.com/g/sfelix-martins/json-exception-handler/?branch=3.0)
[![Tests](https://github.com/sfelix-martins/json-exception-handler/actions/workflows/tests.yml/badge.svg)](https://github.com/sfelix-martins/json-exception-handler/actions/workflows/tests.yml)

Adds methods to your `App\Exceptions\Handler` to treat json responses.
It is most useful if you are building APIs!

## Requirements

* PHP >= 8.1
* Laravel Framework 10.x, 11.x, or 12.x

## JsonAPI

Using [JsonAPI](http://jsonapi.org) standard to responses!

## Features

Default error response:

```json
{
  "errors": [
    {
      "status": "404",
      "code": "13",
      "title": "model_not_found_exception",
      "detail": "User not found",
      "source": {
        "pointer": "data/id"
      }
    }
  ]
}
```

To `Illuminate\Validation\ValidationException`:

```json
{
  "errors": [
    {
      "status": "422",
      "code": "1411",
      "title": "Required validation failed on field name",
      "detail": "The name field is required.",
      "source": {
        "pointer": "name"
      }
    },
    {
      "status": "422",
      "code": "1421",
      "title": "Email validation failed on field email",
      "detail": "The email must be a valid email address.",
      "source": {
        "pointer": "email"
      }
    }
  ]
}
```

### Treated Exceptions

- `Illuminate\Auth\Access\AuthorizationException`
- `Illuminate\Auth\AuthenticationException`
- `Illuminate\Database\Eloquent\ModelNotFoundException`
- `Illuminate\Validation\ValidationException`
- `Laravel\Passport\Exceptions\MissingScopeException`
- `League\OAuth2\Server\Exception\OAuthServerException`
- `Symfony\Component\HttpKernel\Exception\NotFoundHttpException`
- `Symfony\Component\HttpKernel\Exception\BadRequestHttpException`

## Installing and configuring

Install the package

```console
$ composer require sfelix-martins/json-exception-handler
```

The package uses Laravel's auto-discovery feature, so the service provider will be automatically registered.

Publish the config to set your own exception codes

```sh
$ php artisan vendor:publish --provider="SMartins\Exceptions\JsonHandlerServiceProvider"
```

Set your exception codes on `config/json-exception-handler.php` on codes array.

You can add more fields and codes to `validation_fields` array.

You can add too your models on lang packages to return the Not Found response translated correctly.

In `resources/lang/vendor/exception/lang/$locale` in `exceptions` file you can set on `models` array. Example:

```php
    'models' => [
        'User' => 'Usuário',
        'Article' => 'Artigo',
    ]
```

## Using

Use the trait on your `App\Exception\Handler` and add method `jsonResponse()`
passing the `$exception` if `$request` expects a json response on `render()` method

```php
use SMartins\Exceptions\JsonHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use JsonHandler;

    // ...

    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {
            return $this->jsonResponse($e);
        }

        return parent::render($request, $e);
    }

    // ...
```

### Use sample

```php
class UserController extends Controller
{
    // ...

    public function store(Request $request)
    {
        // Validation
        $request->validate($this->rules);

        // or
        $this->validate($request, $this->rules);

        //and or
        Validator::make($request->all(), $this->rules)->validate();

        if (condition()) {
            // Generate response with http code and message
            abort(403, 'Action forbidden!');
        }

        if (anotherCondition()) {
            // Generate response with message and code
            throw new TokenMismatchException("Error Processing Request", 10);
        }
    }

    public function show($id)
    {
        // If not found the default response is called
        $user = User::findOrFail($id);

        // Gate define on AuthServiceProvider
        // Generate an AuthorizationException if fail
        $this->authorize('users.view', $user->id);
    }

```

## Extending

You can create your own handler for any Exception. E.g.:

- Create a Handler class that extends `AbstractHandler`:

```php
namespace App\Exceptions;

use GuzzleHttp\Exception\ClientException;
use SMartins\Exceptions\Handlers\AbstractHandler;
use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;

class GuzzleClientHandler extends AbstractHandler
{
    public function __construct(ClientException $e)
    {
        parent::__construct($e);
    }

    public function handle(): ErrorHandledInterface|ErrorHandledCollectionInterface
    {
        return (new Error)->setStatus((string) $this->getStatusCode())
            ->setCode((string) $this->getCode())
            ->setSource((new Source())->setPointer($this->getDefaultPointer()))
            ->setTitle($this->getDefaultTitle())
            ->setDetail($this->exception->getMessage());
    }

    public function getCode(string $type = 'default'): int|string
    {
        // You can add a new type of code on `config/json-exception-handlers.php`
        return config('json-exception-handler.codes.client.default');
    }
}
```

- For returning multiple errors:

```php
namespace App\Exceptions;

use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\JsonApi\ErrorCollection;
use SMartins\Exceptions\Handlers\AbstractHandler;
use SMartins\Exceptions\Response\ErrorHandledCollectionInterface;
use SMartins\Exceptions\Response\ErrorHandledInterface;

class MyCustomizedHandler extends AbstractHandler
{
    public function __construct(MyCustomizedException $e)
    {
        parent::__construct($e);
    }

    public function handle(): ErrorHandledInterface|ErrorHandledCollectionInterface
    {
        $errors = (new ErrorCollection)->setStatusCode('400');

        $exceptions = $this->exception->getExceptions();

        foreach ($exceptions as $exception) {
            $error = (new Error)->setStatus('422')
                ->setSource((new Source())->setPointer($this->getDefaultPointer()))
                ->setTitle($this->getDefaultTitle())
                ->setDetail($exception->getMessage());

            $errors->push($error);
        }

        return $errors;
    }
}
```

- Now just register your customized handler on `App\Exception\Handler` file on attribute `exceptionHandlers`. E.g:

```php
namespace App\Exceptions;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use SMartins\Exceptions\JsonHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use JsonHandler;

    protected $exceptionHandlers = [
        // Set on key the exception and on value the handler.
        ClientException::class => GuzzleClientHandler::class,
    ];
```

## Upgrading from 2.x

If you're upgrading from version 2.x, note the following changes:

1. PHP 8.1+ is now required
2. Laravel 10, 11, or 12 is required
3. The `render()` method signature now uses `Throwable` instead of `Exception`
4. All handler classes now use strict types and proper return type declarations
5. Status codes are now always strings in the JSON response (as per JsonAPI spec)

## Response References:

- http://jsonapi.org/format/#errors

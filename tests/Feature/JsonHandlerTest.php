<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Tests\Feature;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use SMartins\Exceptions\Tests\Fixtures\User as Model;
use SMartins\Exceptions\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonHandlerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'exceptions']);

        $this->setUpRoutes();
    }

    public function setUpRoutes(): void
    {
        Route::get('default_exception', function (Request $request) {
            throw new Exception('Test message', 1);
        });

        Route::get('/model_not_found', function (Request $request) {
            Model::findOrFail(1);
        });

        Route::middleware('auth')->get('/authentication', function (Request $request) {
            //
        });

        Route::get('authorization', function (Request $request) {
            throw new AuthorizationException('Forbidden');
        });

        Route::get('validation', function (Request $request) {
            Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'email',
            ])->validate();
        });

        Route::get('bad_request', function (Request $request) {
            throw new BadRequestHttpException('Bad Request');
        });
    }

    #[Test]
    public function testThrowsDefaultException(): void
    {
        $this->json('GET', 'default_exception')
            ->assertStatus(500)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    #[Test]
    public function testThrowsModelNotFoundException(): void
    {
        $this->json('GET', 'model_not_found')
            ->assertStatus(404)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    #[Test]
    public function testThrowsAuthenticationException(): void
    {
        $this->json('GET', 'authentication')
            ->assertStatus(401)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    #[Test]
    public function testThrowsAuthorizationException(): void
    {
        $this->json('GET', 'authorization')
            ->assertStatus(403)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    #[Test]
    public function testThrowsValidationExceptions(): void
    {
        $params = ['email' => str_repeat('a', 11)];

        $this->json('GET', 'validation', $params)
            ->assertStatus(422)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    #[Test]
    public function testThrowsBadRequestHttpException(): void
    {
        $this->json('GET', 'bad_request')
            ->assertStatus(400)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    #[Test]
    public function testThrowsNotFoundHttpException(): void
    {
        $this->json('GET', 'not_found_route')
            ->assertStatus(404)
            ->assertJsonStructure($this->defaultErrorStructure());
    }

    /**
     * The default json response error structure.
     *
     * @return array<string, array<int, array<string, array<int, string>|string>>>
     */
    public function defaultErrorStructure(): array
    {
        return [
            'errors' => [[
                'status',
                'code',
                'title',
                'detail',
                'source' => ['pointer'],
            ]],
        ];
    }
}

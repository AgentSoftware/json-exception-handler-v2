<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Tests\Unit\Handlers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\Attributes\Test;
use SMartins\Exceptions\Handlers\ModelNotFoundHandler;
use SMartins\Exceptions\Tests\TestCase;

class ModelNotFoundHandlerTest extends TestCase
{
    #[Test]
    public function it_will_return_the_model_class_name_without_translations(): void
    {
        $exception = (new ModelNotFoundException())->setModel(NotTranslated::class);

        $handler = new ModelNotFoundHandler($exception);

        $error = $handler->handle();

        $this->assertEquals('404', $error->getStatus());
        $this->assertEquals(
            'NotTranslated not found',
            $error->getDetail()
        );
    }
}

class NotTranslated extends Model
{
}

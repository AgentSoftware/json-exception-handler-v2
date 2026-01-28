<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Tests\Unit;

use Exception;
use PHPUnit\Framework\Attributes\Test;
use SMartins\Exceptions\Handlers\Handler;
use SMartins\Exceptions\Tests\TestCase;
use TypeError;

class AbstractHandlerTest extends TestCase
{
    #[Test]
    public function testShouldReturnsTheHandlerClassOnGetExceptionHandler(): void
    {
        $handler = new Handler(new Exception);

        $this->assertInstanceOf(Handler::class, $handler->getExceptionHandler());
    }

    #[Test]
    public function testGetDefaultHandler(): void
    {
        $handler = new Handler(new Exception);

        $this->assertInstanceOf(Handler::class, $handler->getDefaultHandler());
    }

    #[Test]
    public function testValidateHandledExceptionWithInvalidArgument(): void
    {
        $this->expectException(TypeError::class);

        $handler = new Handler(new Exception);
        // @phpstan-ignore-next-line
        $handler->validatedHandledException('invalid');
    }
}

<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Tests\Unit;

use Exception;
use PHPUnit\Framework\Attributes\Test;
use SMartins\Exceptions\Handlers\Handler;
use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\Tests\TestCase;

class HandlerTest extends TestCase
{
    #[Test]
    public function testHandle(): void
    {
        $exception = new Exception('Test exception', 1);

        $handler = new Handler($exception);
        $this->assertInstanceOf(Error::class, $handler->handle());
    }
}

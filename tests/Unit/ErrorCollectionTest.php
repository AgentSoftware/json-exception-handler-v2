<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use SMartins\Exceptions\JsonApi\ErrorCollection;
use SMartins\Exceptions\Tests\TestCase;

class ErrorCollectionTest extends TestCase
{
    #[Test]
    public function testSetHeaders(): void
    {
        $error = new ErrorCollection;
        $this->assertInstanceOf(ErrorCollection::class, $error->setHeaders(['foo' => 'bar']));

        $this->assertEquals($error->getHeaders(), ['foo' => 'bar']);
    }
}

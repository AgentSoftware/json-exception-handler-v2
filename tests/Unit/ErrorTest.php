<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use SMartins\Exceptions\JsonApi\Error;
use SMartins\Exceptions\JsonApi\ErrorCollection;
use SMartins\Exceptions\JsonApi\Links;
use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\Tests\TestCase;

class ErrorTest extends TestCase
{
    #[Test]
    public function testGetAndSetId(): void
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setId('1'));
        $this->assertEquals('1', $error->getId());
    }

    #[Test]
    public function testGetAndSetLinks(): void
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setLinks($links = new Links));
        $this->assertEquals($links, $error->getLinks());
    }

    #[Test]
    public function testGetAndSetCode(): void
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setCode('1'));
        $this->assertEquals('1', $error->getCode());
    }

    #[Test]
    public function testGetAndSetTitle(): void
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setTitle('tests'));
        $this->assertEquals('tests', $error->getTitle());
    }

    #[Test]
    public function testGetAndSetDetail(): void
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setDetail('detail'));
        $this->assertEquals('detail', $error->getDetail());
    }

    #[Test]
    public function testGetAndSerSource(): void
    {
        $error = new Error;
        $this->assertInstanceOf(Error::class, $error->setSource($source = new Source));
        $this->assertEquals($source, $error->getSource());
    }

    #[Test]
    public function testToCollection(): void
    {
        $error = new Error;
        $this->assertInstanceOf(ErrorCollection::class, $error->toCollection());
    }
}

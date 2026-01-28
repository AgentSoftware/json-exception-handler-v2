<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Tests\Unit;

use SMartins\Exceptions\JsonApi\Source;
use SMartins\Exceptions\Tests\GettersAndSetters;
use SMartins\Exceptions\Tests\TestCase;

class SourceTest extends TestCase
{
    use GettersAndSetters;

    /**
     * @var class-string
     */
    protected string $classToTest = Source::class;
}

<?php

declare(strict_types=1);

namespace SMartins\Exceptions\Tests;

use InvalidArgumentException;
use ReflectionClass;

trait GettersAndSetters
{
    /**
     * @throws \ReflectionException
     */
    public function testGettersAndSetters(): void
    {
        if (! property_exists($this, 'classToTest')) {
            throw new InvalidArgumentException('Please define the property $classToTest.');
        }

        $class = new $this->classToTest;
        $reflect = new ReflectionClass($class);
        $properties = $reflect->getProperties();

        foreach ($properties as $property) {
            $setter = 'set'.ucfirst($property->getName());
            $getter = 'get'.ucfirst($property->getName());

            $this->assertInstanceOf($this->classToTest, $class->{$setter}('test'));
            $this->assertEquals('test', $class->{$getter}());
        }
    }
}

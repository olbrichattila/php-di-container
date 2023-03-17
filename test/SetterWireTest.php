<?php

declare(strict_types=1);

namespace Aolbrich\Test;

use Aolbrich\PhpDiContainer\Container;
use PHPUnit\Framework\TestCase;
use Aolbrich\Test\Fixtures\AutoWireInterface;
use Aolbrich\Test\Fixtures\ClassSetterAutoWire;
use Aolbrich\Test\Fixtures\ClassWithoutDependencyImplementsInterface;

require_once 'fixtureLoader.php';

class SetterWireTest extends TestCase
{
    public function testClassResolvesWithAutowire(): void
    {
        $container = new Container(
            [AutoWireInterface::class => ClassWithoutDependencyImplementsInterface::class]
        );
        $class = $container->resolveClass(ClassSetterAutoWire::class);

        $this->assertTrue($class->getResult());
    }
}

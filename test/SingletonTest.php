<?php

declare(strict_types=1);

namespace Aolbrich\Test;

use Aolbrich\PhpDiContainer\Container;
use PHPUnit\Framework\TestCase;
use Aolbrich\Test\Fixtures\ClassWithoutDependency;
use Aolbrich\Test\Fixtures\ClassAutoWiringParentClass;
use Aolbrich\Test\Fixtures\AutoWireInterface;
use Aolbrich\Test\Fixtures\ClassAutoWiringParentInterface;
use Aolbrich\Test\Fixtures\ClassWithoutDependencyImplementsInterface;
use Aolbrich\Test\Fixtures\ClassRecursiveDepenendies;
use ReflectionException;

require_once 'fixtureLoader.php';

class singletonTest extends TestCase
{
    public function testSimpleClassResolvesAsSingletonOnlyWhenCreteadWithSingleton(): void
    {
        $container = new Container();
        $class1 = $container->get(ClassWithoutDependency::class);
        $class2 = $container->get(ClassWithoutDependency::class);

        // Assert two different classes returned
        $this->assertFalse($class1 === $class2);

        $class3 = $container->singleton(ClassWithoutDependency::class);
        $class4 = $container->singleton(ClassWithoutDependency::class);

        // Assert the same class is returned
        $this->assertTrue($class3 === $class4);
    }
}

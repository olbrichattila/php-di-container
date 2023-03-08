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

require_once 'fixtureLoader.php';

class ContainerTest extends TestCase
{
    public function testCanBeBuilt(): void
    {
        // Assert should not be an error
        self::assertInstanceOf(Container::class, new Container());
    }

    public function testHasReturnsFalseIfIdNotSet(): void
    {
        $container = new Container();
        $has = $container->has('test');

        $this->assertFalse($has);
    }

    public function testHasReturnsTrueIfIdSet(): void
    {
        $container = new Container();
        $has = $container->set('test', 'dummy');
        $has = $container->has('test');

        $this->assertTrue($has);
    }

    public function testSimpleClassResolves(): void
    {
        $container = new Container();
        $class = $container->get(ClassWithoutDependency::class);

        $this->assertInstanceOf(ClassWithoutDependency::class, $class);
        $this->assertTrue($class->getResult());
    }

    public function testInvalidClassTrhorwError(): void
    {
        $this->expectException(\ReflectionException::class);
        $container = new Container();
        $class = $container->get('InvalidClassName');
    }

    public function testResolvesAutoWiredClassInConstructor(): void
    {
        $container = new Container();
        $class = $container->get(ClassAutoWiringParentClass::class);

        $this->assertInstanceOf(ClassAutoWiringParentClass::class, $class);
        $this->assertTrue($class->getResult());
    }

    public function testResolvesAutoWiredInterfaceInConstructor(): void
    {
        $container = new Container();
        $container->set(AutoWireInterface::class, ClassWithoutDependencyImplementsInterface::class);
        $class = $container->get(ClassAutoWiringParentInterface::class);

        $this->assertInstanceOf(ClassAutoWiringParentInterface::class, $class);
        $this->assertTrue($class->getResult());
    }

    public function testResolvesRecursiely(): void
    {
        $container = new Container();
        $container->set(AutoWireInterface::class, ClassWithoutDependencyImplementsInterface::class);
        $class = $container->get(ClassRecursiveDepenendies::class);

        $this->assertInstanceOf(ClassRecursiveDepenendies::class, $class);
        $this->assertTrue($class->getResult());
    }
}

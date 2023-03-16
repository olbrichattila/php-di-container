<?php

declare(strict_types=1);

namespace Aolbrich\Test;

use Aolbrich\PhpDiContainer\Container;
use PHPUnit\Framework\TestCase;
use Aolbrich\Test\Fixtures\ClassForFunctionLevelResolve;

require_once 'fixtureLoader.php';

class ClassResolverTest extends TestCase
{
    public function testSimpleClassResolves(): void
    {
        $container = new Container();
        $class = $container->resolveClass(ClassForFunctionLevelResolve::class);

        $this->assertTrue($class->getResult());
    }
}

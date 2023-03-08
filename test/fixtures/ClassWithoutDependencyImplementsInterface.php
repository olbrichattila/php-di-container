<?php

declare(strict_types=1);

namespace Aolbrich\Test\Fixtures;

class ClassWithoutDependencyImplementsInterface extends ClassWithoutDependency implements AutoWireInterface
{
}

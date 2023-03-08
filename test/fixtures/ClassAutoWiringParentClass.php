<?php

declare(strict_types=1);

namespace Aolbrich\Test\Fixtures;

class ClassAutoWiringParentClass
{
    public function __construct(private readonly ClassWithoutDependency $class)
    {
    }

    public function getResult(): bool
    {
        return $this->class->getResult();
    }
}

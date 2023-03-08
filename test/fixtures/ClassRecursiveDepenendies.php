<?php

declare(strict_types=1);

namespace Aolbrich\Test\Fixtures;

class ClassRecursiveDepenendies
{
    public function __construct(private readonly ClassAutoWiringParentClass $class)
    {
    }

    public function getResult(): bool
    {
        return $this->class->getResult();
    }
}

<?php

declare(strict_types=1);

namespace Aolbrich\Test\Fixtures;

class ClassAutoWiringParentInterface
{
    public function __construct(private readonly AutoWireInterface $class)
    {
    }

    public function getResult(): bool
    {
        return $this->class->getResult();
    }
}

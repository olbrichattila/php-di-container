<?php

declare(strict_types=1);

namespace Aolbrich\Test\Fixtures;

class ClassWithoutDependency
{
    public function getResult(): bool
    {
        return true;
    }
}

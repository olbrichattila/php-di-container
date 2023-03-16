<?php

declare(strict_types=1);

namespace Aolbrich\Test\Fixtures;

/**
 * Example resolving with interface declaration and mapping
 */
class ClassForFunctionLevelResolve
{
    /**
     * @Autowire
     */
    public function getResult(ClassWithoutDependency $class): bool
    {
        return $class->getResult();
    }
}



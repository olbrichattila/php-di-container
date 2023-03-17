<?php

declare(strict_types=1);

namespace Aolbrich\Test\Fixtures;

class ClassSetterAutoWire
{
    private AutoWireInterface $dependency;

    /**
     * @Autowire
     */
    public function setDepencency(AutoWireInterface $dependency)
    {
        $this->dependency = $dependency;
    }

    public function getResult(): bool
    {
        return $this->dependency->getResult();
    }
}

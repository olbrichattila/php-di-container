<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;

/**
 * It returns a new anonymous class, which will resolve all parameters for every function call
 */
class ClassResolver
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    public function resolveClass(string $className): Object
    {
        $class = $this->container->get($className);

        return $this->bindAllToNewClass($class, $className);
    }

    protected function bindAllToNewClass(Object $class, string $className): Object
    {
        return new class ($class, $className, $this->container) {
            public function __construct(
                private readonly Object $class,
                private readonly string $className,
                private readonly Container $container
            ) {
            }

            public function __call(string $methodName, array $params): mixed
            {
                if (strtolower($methodName) === '__construct') {
                    return null;
                }
                $reflectionClass = new ReflectionClass($this->className);
                $method = $reflectionClass->getMethod($methodName);
                $annotations = $this->container->getAnnotations($method);
                
                if (in_array('@autowire', $annotations)) {
                    $dependencies = $this->container->getDependencies(
                        $method->getParameters(),
                        $this->className
                    );
                    
                    return call_user_func_array([$this->class, $methodName], $dependencies);
                }

                return call_user_func_array([$this->class, $methodName], $params);
            }
        };
    }
}

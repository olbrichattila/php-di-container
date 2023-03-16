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
                // $reflectionClass = new ReflectionClass($this->className);
                $method = $this->getMethod($methodName);
                $annotations = $this->getAnnotations($method);
                
                if (in_array('@autowire', $annotations)) {
                    $parameters = $method->getParameters();
                    $dependencies = $this->container->getDependencies($parameters, $this->className);
                    return call_user_func_array([$this->class, $methodName], $dependencies);
                }

                return call_user_func_array([$this->class, $methodName], $params);
            }

            private function getMethod(string $methodName): ReflectionMethod
            {
                $reflectionClass = new ReflectionClass($this->className);

                return $reflectionClass->getMethod($methodName);
            }

            private function getAnnotations(ReflectionMethod $method): array
            {
                $docComment = $method->getDocComment();
                if (!$docComment) {
                    return [];
                }
                preg_match_all('#@(.*?)\n#s', $docComment, $annotations);
                return array_map(
                    'trim',
                    array_map(
                        'strtolower',
                        reset($annotations)
                    )
                );
            }
        };
    }
}

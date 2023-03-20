<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer;

use Psr\Container\ContainerInterface;
use Aolbrich\PhpDiContainer\Exceptions\ContainerException;
use Aolbrich\PhpDiContainer\Exceptions\NotFoundException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionNamedType;

/**
 * Consructor Dependency Injection container
 */
class Container implements ContainerInterface
{
    protected array $bindings = [];
    protected array $singletons = [];

    public function __construct(array $definitions = [])
    {
        $this->setDefinitions($definitions);
    }

    public function setDefinitions(array $definitions = []): void
    {
        $this->bindings = [];

        foreach($definitions as $id => $concrete) {
            $this->set($id, $concrete);
        }
    }

    public function get(string $id, array $extraBindingParameters = [])
    {
        if (array_key_exists($id, $this->singletons) && $this->singletons[$id] !== null) {
            return $this->singletons[$id];
        }
        
        if ($this->has($id)) {
            $binding = $this->bindings[$id];
            if (is_callable($binding)) {
                if (!array_key_exists($id, $this->singletons)) {
                    return $binding($this);
                } else if($this->singletons[$id] === null) {
                    $resolved = $this->resolveWithSetterInjections($id, $extraBindingParameters);
                    $this->singletons[$id] = $resolved;

                    return $resolved;
                }
            }

            $id = $binding;
        }

        return $this->resolveWithSetterInjections($id, $extraBindingParameters);
    }

    public function singleton(string $id, array $extraBindingParameters = [])
    {
        if (!array_key_exists($id, $this->singletons)) {
            $this->singletons[$id] = null;
            $this->singletons[$id] = $this->get($id, $extraBindingParameters);
        }

        return $this->singletons[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }

    public function set(string $id, callable|string $concrete): void
    {
        $this->bindings[$id] = $concrete;
    }

    public function resolve(string $id, array $extraBindingParameters = []): mixed
    {
        $reflectionClass = $this->getReflectionClass($id);
        if (!$constructor = $reflectionClass->getConstructor()) {
            return new $id();
        }

        if (!$parameters = $constructor->getParameters()) {
            return new $id();
        }

        return $reflectionClass->newInstanceArgs(
            $this->getDependencies($parameters,$id, $extraBindingParameters)
        );
    }

    protected function resolveWithSetterInjections(string $id, array $extraBindingParameters): mixed
    {
        $resolved = $this->resolve($id, $extraBindingParameters);
        
        return $this->resolveSetterInjections($resolved);
    }

    protected function getReflectionClass(string $id): ReflectionClass
    {
        $reflectionClass = new ReflectionClass($id);
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException('Class ' . $id . ' is not instantiable');
        }

        return $reflectionClass;
    }

    public function getDependencies(array $parameters, string $id, array $extraBindingParameters = []): array
    {
        return array_map(function (ReflectionParameter $parameter) use ($id, $extraBindingParameters): mixed {
            $parameterType = $parameter->getType();
            $parameterName = $parameter->getName();
            $parameterTypeName = $parameterType ? $parameterType->getName() : 'unknown';
            $callParameter = $extraBindingParameters[$parameterName] ?? null;
            if ($callParameter !== null) {
                if ($parameterType !== 'unknown' && $parameterType instanceof ReflectionNamedType) {
                    $callParameter = $this->converArgument($parameterTypeName, (string)$callParameter);
                    return $callParameter;
                }

                return $callParameter;
            } elseif ($parameterType instanceof ReflectionNamedType && !$parameterType->isBuiltIn()) {
                if ($parameterTypeName === $id) {
                    throw new ContainerException('Class ' . $id . ' is circular referenced');
                }
                return $this->get($parameterTypeName);
            }
            

            throw new NotFoundException('Failed to resolve ' . $id . ' parameter type "' . $parameterTypeName . '" cannot be resolved');
        }, $parameters);
    }

    public function getAnnotations(ReflectionMethod $method): array
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

    public function resolveClass(string $className): Object
    {
        $classResolver = new ClassResolver($this);
        
        return $classResolver->resolveClass($className);
    }

    private function resolveSetterInjections(Object $class): Object
    {
        $className = get_class($class);
        $reflection = $this->getReflectionClass($className);
        $methods = $reflection->getMethods();
        
        foreach ($methods as $method) {
            $methodName = $method->getName();
            $annotations = $this->getAnnotations($method);
            
            if (
                preg_match('/^set.*$/i', $methodName)
                && in_array('@autowire', $annotations)
            ) {
                if ($method->isPublic() === false) {
                    throw new ContainerException('In case of setter injection the method must be public');
                }

                $dependencies = $this->getDependencies(
                    $method->getParameters(),
                    $className
                );
                call_user_func_array([$class, $methodName], $dependencies);
            }
        }

        return $class;
    }

    protected function converArgument(string $type, string $argument): mixed
    {
        $result = $argument;
        switch ($type) {
            case 'int':
                $result = intval($result);
                break;
            case 'float':
                $result = floatval($result);
        }

        return $result;
    }
}

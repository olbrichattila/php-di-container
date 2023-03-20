<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

use Aolbrich\PhpDiContainer\Container;

require_once realpath(__DIR__ . '/../../vendor') . '/autoload.php';

$definitions = [
    ExampleServiceInterface::class => ExampleService::class,
];

$container = new Container($definitions);

/*
    It is also possible to load the definitions this way

    $container->setDefinitions($definitions);

    or adding a new one, which does not overwrite the existing bindings

    $container->set(ExampleServiceInterface::class, ExampleService::class);

    or use closure (can be used in the array definition as well)

    We also could use binding as a closure

    $container->set(ExampleServiceInterface::class, function(Container $container) {
        return $container->get(ExampleService::class);
    });
*/

// Resolve class
$class = $container->get(ExampleClass::class);
echo $class->getResponse();

// Resolve all functions in class with @autowire annotation
$class2 = $container->resolveClass(ExampleClassForFunctionLevelResolve::class);
echo $class2->getResponseWithAutowiredParams();

// Resolve setters with @autowire
$class3 = $container->get(ExampleSetterAutowireClass::class);
echo $class3->getResponse();

// Resolve as non Singletone
$class = $container->get(ExampleSetterAutowireClass::class);
$class2 = $container->get(ExampleSetterAutowireClass::class);

echo $class === $class2 ? "Same class instance created\n" : "Different class instance created\n";

// Resolve as Singletone
$class = $container->singletone(ExampleSetterAutowireClass::class);
$class2 = $container->singletone(ExampleSetterAutowireClass::class);

echo $class === $class2 ? "Same class instance created\n" : "Different class instance created\n";


// Autowire as Singleton
$container->set(ExampleService::class, function(Container $container) {
    return $container->singletone(ExampleService::class);
});

$class = $container->get(ExampleService::class);
$class2 = $container->get(ExampleService::class);
$class3 = $container->get(ExampleService::class);
$class4 = $container->singletone(ExampleService::class);

echo $class === $class2 ? "Same class instance created\n" : "Different class instance created\n";
echo $class === $class3 ? "Same class instance created\n" : "Different class instance created\n";
echo $class === $class4 ? "Same class instance created\n" : "Different class instance created\n";

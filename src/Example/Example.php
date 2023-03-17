<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

use Aolbrich\PhpDiContainer\Container;

require_once realpath(__DIR__ . '/../../vendor') . '/autoload.php';

$definitions = [
    ExampleServiceInterface::class => ExampleService::class,
];

$container = new Container($definitions);

$container->setDefinitions($definitions);
// $container->set(ExampleServiceInterface::class, ExampleService::class);

/*
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

$class3 = $container->resolveClass(ExampleSetterAutowireClass::class);
echo $class3->getResponse();


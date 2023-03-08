<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Example;

use Aolbrich\PhpDiContainer\Container;

require_once realpath(__DIR__ . '/../../vendor') . '/autoload.php';

$container = new Container();
$container->set(ExampleServiceInterface::class, ExampleService::class);

/*
    We also could use binding as a closure

    $container->set(ExampleServiceInterface::class, function(Container $container) {
        return $container->get(ExampleService::class);
    });
*/
$class = $container->get(ExampleClass::class);

echo $class->getResponse();

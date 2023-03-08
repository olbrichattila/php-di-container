<?php

declare(strict_types=1);

namespace Aolbrich\PhpDiContainer\Exceptions;

use Psr\Container\ContainerExceptionInterface;
use Exception;

class ContainerException extends Exception implements ContainerExceptionInterface
{
}

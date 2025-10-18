<?php

declare(strict_types=1);

namespace Fugikzl\Container\Internal;

use Psr\Container\ContainerInterface;

interface DefinitionInterface
{
    public function resolve(ContainerInterface $container, bool $fresh = true): mixed;
}

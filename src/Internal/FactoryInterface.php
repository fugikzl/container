<?php

declare(strict_types=1);

namespace Fugikzl\Container\Internal;

use Psr\Container\ContainerInterface;

interface FactoryInterface
{
    public function __invoke(ContainerInterface $container, ?string $requestedName = null): mixed;
}

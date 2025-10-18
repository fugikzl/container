<?php

declare(strict_types=1);

namespace Fugikzl\Container\Internal;

use Override;

final class ConcreteDefinition implements DefinitionInterface
{
    public function __construct(
        private readonly string $id,
        private readonly mixed $value,
    ) {
    }

    #[Override]
    public function resolve(\Psr\Container\ContainerInterface $container, bool $fresh = true): mixed
    {
        return $this->value;
    }
}

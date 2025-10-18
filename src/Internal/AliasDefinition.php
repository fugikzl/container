<?php

declare(strict_types=1);

namespace Fugikzl\Container\Internal;

use Override;

final class AliasDefinition implements DefinitionInterface
{
    private mixed $value;

    public function __construct(
        private readonly string $id,
        private readonly string|object $alias,
    ) {
        $this->value = new Undefined();
    }

    #[Override]
    public function resolve(\Psr\Container\ContainerInterface $container, bool $fresh = true): mixed
    {
        if ($fresh === false) {
            if ($this->value instanceof Undefined) {
                $this->value = $container->get($this->alias);
                return $this->value;
            }

            return $this->value;
        }

        $this->value = $container->get($this->alias);
        return $this->value;
    }
}

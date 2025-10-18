<?php

declare(strict_types=1);

namespace Fugikzl\Container\Internal;

use InvalidArgumentException;
use Override;
use Psr\Container\ContainerInterface;
use ReflectionClass;

final class FactoryDefinition implements DefinitionInterface
{
    private mixed $value;

    public function __construct(
        private readonly string $id,
        private readonly string|object $factory,
    ) {
        $this->value = new Undefined();
    }

    #[Override]
    public function resolve(ContainerInterface $container, bool $fresh = true): mixed
    {
        if ($fresh === false) {
            if ($this->value instanceof Undefined) {
                $this->value = $this->resolveValue($container);
                return $this->value;
            }

            return $this->value;
        }

        $this->value = $this->resolveValue($container);
        return $this->value;
    }

    private function resolveValue(ContainerInterface $container): mixed
    {
        $factory = $this->resolveFactory($container);

        return $factory->__invoke($container, $this->id);
    }

    private function resolveFactory(ContainerInterface $container): FactoryInterface
    {
        if (\is_string($this->factory) === true) {
            if (\class_exists($this->factory) === false) {
                throw new InvalidArgumentException(\sprintf('Invalid factory string provided. "%s" is not class-string', $this->factory));
            }

            $rc = new ReflectionClass($this->factory);
            if ($rc->implementsInterface(FactoryInterface::class) === false) {
                throw new InvalidArgumentException(\sprintf('Invalid factory class-string provided. "%s" not implements %s', $this->factory, FactoryInterface::class));
            }

            return $container->get($this->factory);
        }

        if ($this->factory instanceof FactoryInterface) {
            return $this->factory;
        }

        throw new InvalidArgumentException(\sprintf('Invalid factory object provided. Object instance of %s does not implements %s', $this->factory::class, FactoryInterface::class));
    }
}

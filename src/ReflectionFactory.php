<?php

declare(strict_types=1);

namespace Fugikzl\Container;

use Fugikzl\Container\Internal\Exception\UnableToResolveException;
use Fugikzl\Container\Internal\FactoryInterface;
use Override;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

final class ReflectionFactory implements FactoryInterface
{
    #[Override]
    public function __invoke(ContainerInterface $container, ?string $requestedName = null): mixed
    {
        if ($requestedName === null) {
            $this->throwException($requestedName);
        }
        /** @var string $requestedName */

        if ($this->canCreate($requestedName) === false) {
            $this->throwException($requestedName);
        }

        $rc = new ReflectionClass($requestedName);
        $constructor = $rc->getConstructor();

        if ($constructor === null) {
            return new $requestedName();
        }

        $reflectionParameters = $constructor->getParameters();

        if (empty($reflectionParameters)) {
            return new $requestedName();
        }

        $args = [];
        foreach ($reflectionParameters as $reflectionParameter) {
            $args[] = $this->resolveParameter($reflectionParameter, $container, $requestedName);
        }

        return new $requestedName(...$args);
    }

    private function resolveParameter(ReflectionParameter $parameter, ContainerInterface $container, $requestedName)
    {
        $type = $parameter->getType();
        $type = $type instanceof ReflectionNamedType ? $type->getName() : null;

        if ($type === $requestedName) {
            $this->throwException($requestedName);
        }

        if ($type === null || (is_string($type) && ! class_exists($type) && ! interface_exists($type))) {
            if (! $parameter->isDefaultValueAvailable()) {
                $this->throwException($requestedName);
            }

            return $parameter->getDefaultValue();
        }

        $type = $this->aliases[$type] ?? $type;

        if ($container->has($type)) {
            return $container->get($type);
        }

        if (! $parameter->isOptional()) {
            $this->throwException($requestedName);
        }

        return $parameter->getDefaultValue();
    }


    private function throwException(?string $requestedName = null, ?string $details = null)
    {
        throw new UnableToResolveException($requestedName, $details);
    }

    private function canCreate($requestedName)
    {
        return \class_exists($requestedName) && $this->canCallConstructor($requestedName);
    }

    private function canCallConstructor(string $requestedName): bool
    {
        $constructor = (new ReflectionClass($requestedName))->getConstructor();

        return $constructor === null || $constructor->isPublic();
    }
}

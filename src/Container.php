<?php

declare(strict_types=1);

namespace Fugikzl\Container;

use Fugikzl\Container\Internal\AliasDefinition;
use Fugikzl\Container\Internal\ConcreteDefinition;
use Fugikzl\Container\Internal\DefinitionInterface;
use Fugikzl\Container\Internal\Exception\UnableToResolveException;
use Fugikzl\Container\Internal\FactoryDefinition;
use Fugikzl\Container\Internal\FactoryInterface;
use Override;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var DefinitionInterface[]
     */
    protected $definitions = [];

    protected $singletones = [];

    public function __construct(
        private readonly bool $reflcetionAutowiring = true
    ) {
    }

    public function setDefinition(string $id, DefinitionInterface $definition, bool $singletone = false, bool $override = false): void
    {
        if (isset($this->definitions[$id]) && $override === false) {
            return;
        }

        $this->definitions[$id] = $definition;
        $this->singletones[$id] = $singletone;
    }

    public function setAlias(string $id, string $alias, bool $singletone = false, bool $override = false): void
    {
        $this->setDefinition($id, new AliasDefinition($id, $alias), $singletone, $override);
    }

    /**
     * @param class-string<FactoryInterface>|FactoryInterface $factory
     */
    public function setFactory(string $id, object|string $factory, bool $singletone = false, bool $override = false): void
    {
        $this->setDefinition($id, new FactoryDefinition($id, $factory), $singletone, $override);
    }

    public function setConcrete(string $id, mixed $concrete, bool $override = false): void
    {
        $this->setDefinition($id, new ConcreteDefinition($id, $concrete), true, $override);
    }

    #[Override]
    public function get(string $id)
    {
        if ($this->hasInDefinitions($id)) {
            return $this->definitions[$id]->resolve($this, !($this->singletones[$id] ?? false));
        }

        if ($this->reflcetionAutowiring === false) {
            throw new UnableToResolveException($id);
        }

        return (new ReflectionFactory())->__invoke($this, $id);
    }

    #[Override]
    public function has(string $id): bool
    {
        $hasInDefinitions = $this->hasInDefinitions($id);

        if ($this->reflcetionAutowiring === false) {
            return $hasInDefinitions;
        }

        try {
            $this->get($id);
            return true;
        } catch (\Psr\Container\ContainerExceptionInterface $ce) {
            return false;
        }
    }

    private function hasInDefinitions(string $id): bool
    {
        return isset($this->definitions[$id]);
    }
}

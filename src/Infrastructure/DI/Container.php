<?php

declare(strict_types=1);

namespace Billing\Infrastructure\DI;

use OutOfBoundsException;

/**
 * Class Container
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class Container
{
    /**
     * @var object[]
     */
    protected $objects;
    /**
     * @var \Closure[]
     */
    private $definitions;

    public function __construct($definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get(string $id)
    {
        if (isset($this->objects[$id])) {
            return $this->objects[$id];
        }

        if (!isset($this->definitions[$id])) {
            if (class_exists($id)) {
                return new $id();
            }

            throw new \OutOfBoundsException(sprintf('There is no definition for %s', $id));
        }

        $definition = $this->definitions[$id];

        if ($definition instanceof \Closure) {
            return $this->objects[$id] = \call_user_func($this->definitions[$id], $this);
        }
        if (\is_string($definition)) {
            return $this->objects[$id] = $this->get($definition);
        }

        throw new OutOfBoundsException('Wrong definition for ' . $id);
    }
}

<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Hydrator;

use GeneratedHydrator\Configuration;
use Zend\Hydrator\HydratorInterface;

/**
 * Class BaseHydrator
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
abstract class BaseHydrator implements HydratorInterface
{
    /**
     * @var HydratorInterface[]
     */
    protected $generatedHydrators = [];

    /**
     * @param object $object
     * @return HydratorInterface
     */
    protected function getGeneratedHydrator($object): HydratorInterface
    {
        $class = get_class($object);
        if (empty($this->generatedHydrators[$class])) {
            $config = new Configuration($class);
            $hydratorClass = $config->createFactory()->getHydratorClass();

            $this->generatedHydrators[$class] = new $hydratorClass();
        }

        return $this->generatedHydrators[$class];
    }

    /**
     * @param array $data
     * @param object|string $classNameOrObject The object or the class name
     * @return object
     */
    public function hydrate(array $data, $classNameOrObject)
    {
        $object = $this->ensureObject($classNameOrObject);

        return $this->getGeneratedHydrator($object)->hydrate($data, $object);
    }

    /**
     * @param string|object $classNameOrObject
     * @return object
     */
    protected function ensureObject($classNameOrObject)
    {
        return \is_object($classNameOrObject) ? $classNameOrObject : $this->createEmptyInstance($classNameOrObject);
    }

    /**
     * @param string $className
     * @return object
     * @throws \ReflectionException
     */
    public function createEmptyInstance(string $className)
    {
        $reflection = new \ReflectionClass($className);

        return $reflection->newInstanceWithoutConstructor();
    }
}

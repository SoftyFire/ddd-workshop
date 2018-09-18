<?php

namespace Billing\Infrastructure\Middleware;

use Billing\Infrastructure\DI\Container;
use League\Tactician\Handler\Locator\HandlerLocator;
use ReflectionClass;

/**
 * Class NearbyHandlerLocator.
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
final class NearbyHandlerLocator implements HandlerLocator
{
    /**
     * @var Container
     */
    private $di;

    public function __construct(Container $di)
    {
        $this->di = $di;
    }

    public function getHandlerForCommand($class)
    {
        $reflector = new ReflectionClass($class);
        $dir = \dirname($reflector->getFileName());

        $commandName = $reflector->getShortName();
        $handlerName = substr($commandName, 0, strrpos($commandName, 'Command')) . 'Handler';

        $path = $dir . DIRECTORY_SEPARATOR . $handlerName . '.php';
        if (!is_file($path)) {
            throw new \InvalidArgumentException('Class "' . $handlerName . '" was not found near to ' . $reflector->getName());
        }

        $className = $reflector->getNamespaceName() . '\\' . $handlerName;

        return $this->di->get($className);
    }
}

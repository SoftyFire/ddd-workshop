<?php

namespace Billing\Infrastructure\Event;

use Billing\Infrastructure\DI\Container;

class DomainEventHandler implements ListenerInterface
{
    /**
     * @var Container
     */
    private $container;
    /** @var \Callable[][]|string[][] */
    private $listeners;

    public function __construct($listeners, Container $container)
    {
        $this->container = $container;
        $this->listeners = $listeners;
    }

    /**
     * @param object $event
     */
    public function handle($event): void
    {
        foreach ($this->listeners[\get_class($event)] ?? [] as $handler) {
            if (\is_string($handler)) {
                $handler = $this->container->get($handler);
            }

            $handler($event);
        }
    }

    public function handleAll(array $events): void
    {
        foreach ($events as $event) {
            $this->handle($event);
        }
    }
}


<?php

namespace Billing\Infrastructure\Middleware;

use Billing\Infrastructure\Event\DomainEventHandler;
use Billing\Infrastructure\Event\EventStorage;
use League\Tactician\Middleware;

class EventDispatcherMiddleware implements Middleware
{
    /**
     * @var EventStorage
     */
    private $eventStorage;
    /**
     * @var DomainEventHandler
     */
    private $eventHandler;

    public function __construct(EventStorage $eventStorage, DomainEventHandler $eventHandler)
    {
        $this->eventStorage = $eventStorage;
        $this->eventHandler = $eventHandler;
    }

    /**
     * @param object $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        $result = $next($command);

        $this->eventHandler->handleAll(
            $this->eventStorage->release()
        );

        return $result;
    }
}

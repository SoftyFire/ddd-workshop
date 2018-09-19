<?php

namespace Billing\Infrastructure\Event;

/**
 * Class EventStorage
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class EventStorage
{
    /**
     * @var array
     */
    protected $events = [];

    /**
     * @param object ...$events
     */
    public function store(...$events): void
    {
        $this->events = array_merge($this->events, $events);
    }

    public function release(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}


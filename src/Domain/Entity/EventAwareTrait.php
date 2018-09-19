<?php

declare(strict_types=1);

namespace Billing\Domain\Entity;

trait EventAwareTrait
{
    private $_occurredEvents = [];

    protected function registerThat($event): void
    {
        $this->_occurredEvents[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->_occurredEvents;
        $this->_occurredEvents = [];

        return $events;
    }
}

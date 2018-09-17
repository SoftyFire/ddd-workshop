<?php

declare(strict_types=1);

namespace Billing\Domain\Aggregate;

trait EventsAwareTrait
{
    private $_events = [];

    protected function recordThat($event): void
    {
        $this->_events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->_events;
        $this->_events = [];

        return $events;
    }
}

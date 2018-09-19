<?php


namespace Billing\Infrastructure\Event;


interface ListenerInterface
{
    public function handle($event): void;

    public function handleAll(array $events): void;
}

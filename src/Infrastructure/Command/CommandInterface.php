<?php


namespace Billing\Infrastructure\Command;

use Psr\Http\Message\ServerRequestInterface;

interface CommandInterface
{
    public function loadFromServerRequest(ServerRequestInterface $request): void;

    public function validate(): void;
}

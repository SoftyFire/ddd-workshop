<?php

namespace Billing\Infrastructure\Middleware;

use Billing\Infrastructure\Command\CommandInterface;
use GuzzleHttp\Psr7\ServerRequest;
use League\Tactician\Middleware;

class LoadCommandFromRequestMiddleware implements Middleware
{
    /**
     * @param object $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        if (!$command instanceof CommandInterface) {
            throw new \BadMethodCallException('Middleware ' . __CLASS__ . ' supports only local command classes');
        }

        $command->loadFromServerRequest(ServerRequest::fromGlobals());
        $command->validate();

        return $next($command);
    }
}

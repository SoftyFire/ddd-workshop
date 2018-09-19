<?php

namespace Billing\Infrastructure\Middleware;

use League\Tactician\Middleware;

class ResponseFormatterMiddleware implements Middleware
{
    /**
     * @param object $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        $result = $next($command);
        /** @noinspection ForgottenDebugOutputInspection */
        var_dump($result);
    }
}

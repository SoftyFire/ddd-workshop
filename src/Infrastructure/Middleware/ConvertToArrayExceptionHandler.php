<?php

namespace Billing\Infrastructure\Middleware;

use League\Tactician\Middleware;

class ConvertToArrayExceptionHandler implements Middleware
{
    /**
     * @param object $command
     * @param callable $next
     *
     * @return mixed
     */
    public function execute($command, callable $next)
    {
        try {
            return $next($command);
        } catch (\Throwable $exception) {
            return [
                'message' => $exception->getMessage()
            ];
        }
    }
}

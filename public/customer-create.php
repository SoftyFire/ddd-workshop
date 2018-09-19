<?php

(function () {
    ini_set('display_errors', (string)true);
    ini_set('error_reporting', (string)\E_ALL);

    require dirname(__DIR__) . '/vendor/autoload.php';

    /** @var Billing\Infrastructure\DI\Container $container */
    $container = require '../config/container.php';

    $command = new \Billing\Infrastructure\Command\Customer\CreateCommand();

    /** @var \League\Tactician\CommandBus $bus */
    $bus = $container->get('http-request-bus');
    $bus->handle($command);
})();

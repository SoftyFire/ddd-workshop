<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$container = require __DIR__ . '/container.php';
$entityManager = $container->get(\Doctrine\Common\Persistence\ObjectManager::class);

return ConsoleRunner::createHelperSet($entityManager);

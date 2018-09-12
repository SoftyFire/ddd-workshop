<?php

namespace Billing\Tests\Integration;

use Billing\Infrastructure\DI\Container;

/**
 * Class TestCase
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Container
     */
    protected $container;

    protected function setUp()
    {
        $this->container = require dirname(__DIR__, 2) . '/config/container.php';
    }
}

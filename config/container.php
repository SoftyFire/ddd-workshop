<?php

use Billing\Infrastructure\DI\Container;

$definitions = [
    \Billing\Domain\Repository\InvoiceRepository::class => \Billing\Infrastructure\Repository\JsonInvoiceRepository::class,

    \Billing\Infrastructure\Repository\JsonInvoiceRepository::class => function (Container $container) {
        return new \Billing\Infrastructure\Repository\JsonInvoiceRepository(
            dirname(__DIR__) . '/data/invoices.json',
            $container->get(\Billing\Infrastructure\Hydrator\InvoiceHydrator::class)
        );
    },

    \Billing\Infrastructure\Hydrator\InvoiceHydrator::class => function (Container $container) {
        return new \Billing\Infrastructure\Hydrator\InvoiceHydrator(
            $container->get(\Billing\Infrastructure\Hydrator\LineItemHydrator::class)
        );
    },

    \Billing\Infrastructure\Hydrator\LineItemHydrator::class => function (Container $container) {
        return new \Billing\Infrastructure\Hydrator\LineItemHydrator(
            $container->get(\Billing\Infrastructure\Hydrator\ItemHydrator::class)
        );
    },
];

return new Container($definitions);

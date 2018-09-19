<?php

(function () {
    ini_set('display_errors', (string)true);
    ini_set('error_reporting', (string)\E_ALL);

    require dirname(__DIR__) . '/vendor/autoload.php';

    /** @var Billing\Infrastructure\DI\Container $container */
    $container = require '../config/container.php';
    $invoiceRepository = $container->get(\Billing\Domain\Repository\InvoiceRepository::class);
    $customerRepository = $container->get(\Billing\Domain\Repository\CustomerRepository::class);

    $customerExists = $container->get(\Billing\Domain\Query\CustomerExists::class);

    $customer = \Billing\Domain\Aggregate\Customer::new(
        \Billing\Domain\Value\EmailAddress::fromString('test' . random_int(1000, 2000) . '@example.com'),
        $customerExists
    );
    $customerRepository->persist($customer);

    $invoice = \Billing\Domain\Aggregate\Invoice::new($customer);
    $invoice->addLine(\Billing\Domain\Entity\LineItem::forItem(
        \Billing\Domain\Aggregate\Item::new('Test item', new Money\Money(10000, new \Money\Currency('USD')))
    ));
    $invoiceRepository->persist($invoice);

    $handler = $container->get(\Billing\Infrastructure\Event\ListenerInterface::class);
    $handler->handleAll($invoice->releaseEvents());

    $foo = $invoiceRepository->get($invoice->id());

    /** @noinspection ForgottenDebugOutputInspection */
    var_dump($foo);
})();

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

    \Billing\Domain\Aggregate\Invoice::charge(
        $container->get(TaxCalculationFactory)
                  ->forCustomer($invoice->customer)
    );

    $customer = \Billing\Domain\Aggregate\Customer::new(
        \Billing\Domain\Value\EmailAddress::fromString('test@example.com'),
        $customerExists
    );
    $customerRepository->persist($customer);

    $invoice = \Billing\Domain\Aggregate\Invoice::new($customer);
    $invoice->addLine(\Billing\Domain\Entity\LineItem::forItem(
        \Billing\Domain\Aggregate\Item::new('Test item', new Money\Money(10000, new \Money\Currency('USD')))
    ));
    $invoiceRepository->persist($invoice);

    $foo = $invoiceRepository->get($invoice->id());

    /** @noinspection ForgottenDebugOutputInspection */
    var_dump($foo);
})();

<?php

namespace Billing\Tests\Domain\Aggregate;

use Billing\Domain\Aggregate\Customer;
use Billing\Domain\Aggregate\Invoice;
use Billing\Domain\Event\InvoiceWasCreatedEvent;
use Billing\Domain\Value\EmailAddress;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    public function testInvoiceCreatedEventOccurs()
    {
        $customer = Customer::new(EmailAddress::fromString('foo@bar.com'));

        $invoice = Invoice::new($customer);
        $events = $invoice->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(InvoiceWasCreatedEvent::class, $events[0]);
    }
}

<?php

namespace Billing\Tests\Domain\Aggregate;

use Billing\Domain\Aggregate\Customer;
use Billing\Domain\Aggregate\Invoice;
use Billing\Domain\Event\InvoiceWasCreatedEvent;
use Billing\Domain\Value\EmailAddress;
use Billing\Tests\Stub\Query\StubCustomerExists;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    public function testInvoiceCreatedEventOccurs(): void
    {
        $invoice = Invoice::new($this->getCustomer());
        $events = $invoice->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(InvoiceWasCreatedEvent::class, $events[0]);
    }

    private function getCustomer(): Customer
    {
        $exists = new StubCustomerExists(false);

        return Customer::new(EmailAddress::fromString('foo@bar.com'), $exists);
    }
}

<?php

declare(strict_types=1);

namespace Billing\Domain\Aggregate;

use Billing\Domain\Entity\LineItem;
use Billing\Domain\Event\InvoiceWasCreatedEvent;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Invoice
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
final class Invoice
{
    use EventsAwareTrait;

    /**
     * @var UuidInterface
     */
    private $id;
    /**
     * @var Customer
     */
    private $customer;

    /**
     * @var LineItem[]
     */
    private $lineItems = [];

    private function __construct()
    {
    }

    /**
     * @return UuidInterface
     */
    public function id(): UuidInterface
    {
        return $this->id;
    }

    public static function new(Customer $customer): self
    {
        $invoice = new Invoice();
        $invoice->id = Uuid::uuid4();
        $invoice->customer = $customer;
        $invoice->recordThat(InvoiceWasCreatedEvent::occurred($invoice));

        return $invoice;
    }

    public function addLine(LineItem $lineItem): self
    {
        $lineItem->setInvoice($this);
        $this->lineItems[] = $lineItem;

        return $this;
    }

    /**
     * @return LineItem[]
     */
    public function getLines()
    {
        return $this->lineItems;
    }

    public function customer(): Customer
    {
        return $this->customer;
    }
}

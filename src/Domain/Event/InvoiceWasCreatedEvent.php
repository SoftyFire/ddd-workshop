<?php

namespace Billing\Domain\Event;

use Billing\Domain\Aggregate\Invoice;

/**
 * Class InvoiceWasCreatedEvent
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class InvoiceWasCreatedEvent
{
    /** @var Invoice */
    private $invoice;

    /**
     * @return Invoice
     */
    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }

    private function __construct()
    {
    }

    public static function occurred(Invoice $invoice): self
    {
        $event = new self;
        $event->invoice = $invoice;

        return $event;
    }
}

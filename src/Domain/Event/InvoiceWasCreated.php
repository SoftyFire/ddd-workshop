<?php


namespace Billing\Domain\Event;

use Billing\Domain\Aggregate\Invoice;

final class InvoiceWasCreated
{
    private $target;

    private function __construct($target)
    {
        $this->target = $target;
    }

    public static function occurred(Invoice $invoice): self
    {
        return new self($invoice);
    }

    public function target(): Invoice
    {
        return $this->target;
    }
}

<?php

namespace Billing\Domain\Repository;

use Billing\Domain\Aggregate\Invoice;
use Ramsey\Uuid\UuidInterface;

/**
 * Interface InvoiceRepository
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
interface InvoiceRepository
{
    /**
     * @param UuidInterface $uuid
     * @return Invoice
     * @throws \OutOfRangeException when invoice was not found
     */
    public function get(UuidInterface $uuid): Invoice;

    /**
     * @param Invoice $invoice
     * @throws \Throwable if fails to save invoice
     */
    public function persist(Invoice $invoice): void;
}

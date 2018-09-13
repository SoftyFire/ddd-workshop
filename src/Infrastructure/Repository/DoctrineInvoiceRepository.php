<?php

namespace Billing\Infrastructure\Repository;

use Billing\Domain\Aggregate\Invoice;
use Billing\Domain\Repository\InvoiceRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\UuidInterface;

/**
 * Class DoctrineInvoiceRepository
 */
class DoctrineInvoiceRepository implements InvoiceRepository
{
    /** @var ObjectManager */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function get(UuidInterface $uuid): Invoice
    {
        $invoice = $this->objectManager->find(Invoice::class, $uuid->toString());

        if (!$invoice instanceof Invoice) {
            throw new \OutOfBoundsException(sprintf(
                'Invoice "%s" does not exist',
                $uuid->toString()
            ));
        }

        return $invoice;
    }

    public function persist(Invoice $invoice) : void
    {
        $this->objectManager->persist($invoice);
        $this->objectManager->flush();
    }
}

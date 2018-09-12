<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Repository;

use Billing\Domain\Aggregate\Invoice;
use Billing\Domain\Repository\InvoiceRepository;
use Billing\Infrastructure\Hydrator\InvoiceHydrator;
use Ramsey\Uuid\UuidInterface;

/**
 * Class JsonInvoiceRepository
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class JsonInvoiceRepository implements InvoiceRepository
{
    /**
     * @var string
     */
    private $jsonFileLocation;

    /**
     * @var InvoiceHydrator
     */
    private $hydrator;

    public function __construct(string $jsonFileLocation, InvoiceHydrator $hydrator)
    {
        $this->jsonFileLocation = $jsonFileLocation;
        $this->hydrator = $hydrator;

        $this->initializeDb();
    }

    /**
     * @param UuidInterface $uuid
     * @return Invoice
     * @throws \OutOfRangeException when invoice was not found
     */
    public function get(UuidInterface $uuid): Invoice
    {
        $uuidString = $uuid->toString();
        $db = $this->readJson();

        if (!isset($db[$uuidString])) {
            throw new \OutOfRangeException(sprintf('%s not found', $uuidString));
        }

        return $this->hydrator->hydrate($db[$uuidString], Invoice::class);
    }

    /**
     * @param Invoice $invoice
     * @throws \Throwable if fails to save invoice
     */
    public function persist(Invoice $invoice): void
    {
        $db = $this->readJson();
        $db[$invoice->id()->toString()] = $this->hydrator->extract($invoice);
        if (file_put_contents($this->jsonFileLocation, json_encode($db, JSON_PRETTY_PRINT)) === false) {
            throw new \Exception('Failed to write JSON Database');
        }
    }

    private function readJson(): array
    {
        return json_decode(file_get_contents($this->jsonFileLocation), true);
    }

    private function initializeDb()
    {
        if (!file_exists($this->jsonFileLocation)) {
            file_put_contents($this->jsonFileLocation, '{}');
        }
    }
}

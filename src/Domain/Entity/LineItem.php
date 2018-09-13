<?php

declare(strict_types=1);

namespace Billing\Domain\Entity;

use Billing\Domain\Aggregate\Invoice;
use Billing\Domain\Aggregate\Item;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class LineItem
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
final class LineItem
{
    /**
     * @var UuidInterface
     */
    private $id;
    /**
     * @var Item
     */
    private $item;
    /**
     * @var float
     */
    private $quantity;
    /**
     * @var Invoice
     */
    private $invoice;

    private function __construct()
    {
    }

    public static function forItem(Item $item, float $quantity): self
    {
        $lineItem = new self();
        $lineItem->id = Uuid::uuid4();
        $lineItem->item = $item;
        $lineItem->quantity = $quantity;

        return $lineItem;
    }

    /**
     * @param Invoice $invoice
     * @throws \Exception when invoice is already set
     */
    public function setInvoice(Invoice $invoice): void
    {
        if ($this->invoice !== null) {
            throw new \Exception('Invoice is already set. Can not reassign.');
        }

        $this->invoice = $invoice;
    }
}

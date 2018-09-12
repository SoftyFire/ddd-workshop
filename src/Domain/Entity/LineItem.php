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

    public static function forItem(Item $item, float $quantity = null): LineItem
    {
        $lineItem = new self();
        $lineItem->id = Uuid::uuid4();
        $lineItem->item = $item;
        $lineItem->quantity = $quantity ?? 1;

        return $lineItem;
    }

    /**
     * @return UuidInterface
     */
    public function id(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return Item
     */
    public function item(): Item
    {
        return $this->item;
    }

    /**
     * @return float
     */
    public function quantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param Invoice $invoice
     * @return LineItem
     * @throws \Exception
     */
    public function setInvoice(Invoice $invoice): self
    {
        if ($this->invoice !== null) {
            throw new \Exception('Invoice is already attached');
        }
        $this->invoice = $invoice;

        return $this;
    }
}

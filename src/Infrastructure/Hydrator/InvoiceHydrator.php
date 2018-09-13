<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Hydrator;

use Billing\Domain\Aggregate\Invoice;
use Billing\Domain\Entity\LineItem;
use Ramsey\Uuid\Uuid;

/**
 * Class InvoiceHydrator
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class InvoiceHydrator extends BaseHydrator
{
    /**
     * @var LineItemHydrator
     */
    private $lineItemHydrator;

    public function __construct(LineItemHydrator $lineItemHydrator)
    {
        $this->lineItemHydrator = $lineItemHydrator;
    }

    public function hydrate(array $data, $classNameOrObject)
    {
        $data['id'] = Uuid::fromString($data['id']);
        $data['lineItems'] = array_map(function ($row) {
            return $this->lineItemHydrator->hydrate($row, LineItem::class);
        }, $data['lineItems']);

        /** @var Invoice $invoice */
        $invoice = parent::hydrate($data, $classNameOrObject);
        foreach ($invoice->getLines() as $lineItem) {
            $lineItem->setInvoice($invoice);
        }

        return $invoice;
    }

    /**
     * Extract values from an object
     *
     * @param  Invoice $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'id' => $object->id()->toString(),
            'lineItems' => array_map(function (LineItem $lineItem) {
                return $this->lineItemHydrator->extract($lineItem);
            }, $object->getLines()),
        ];
    }
}

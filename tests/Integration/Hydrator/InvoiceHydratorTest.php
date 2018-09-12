<?php

namespace Billing\Tests\Integration\Hydrator;

use Billing\Domain\Aggregate\Invoice;
use Billing\Domain\Entity\LineItem;
use Billing\Infrastructure\Hydrator\InvoiceHydrator;
use Billing\Tests\Integration\TestCase;

/**
 * Class InvoiceHydrator
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class InvoiceHydratorTest extends TestCase
{
    /**
     * @var InvoiceHydrator
     */
    protected $hydrator;

    protected function setUp()
    {
        parent::setUp();

        $this->hydrator = $this->container->get(InvoiceHydrator::class);
    }

    public function testExtraction()
    {
        $invoice = \Billing\Domain\Aggregate\Invoice::new();
        $item = \Billing\Domain\Aggregate\Item::new('Test item', new \Money\Money(10000, new \Money\Currency('USD')));
        $invoice->addLine(\Billing\Domain\Entity\LineItem::forItem($item));

        $serialized = [
            'id' => $invoice->id()->toString(),
            'lineItems' => [
                [
                    'item' => [
                        'id' => $item->id()->toString(),
                        'name' => 'Test item',
                        'price' => [
                            'amount' => 10000,
                            'currencyCode' => 'USD',
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
        ];

        $this->assertEquals($this->hydrator->extract($invoice), $serialized);

        /** @var Invoice $restored */
        $restored = $this->hydrator->hydrate($serialized, Invoice::class);
        $this->assertTrue($restored->id()->equals($invoice->id()));

        /** @var LineItem[] $restoredLines */
        $restoredLines = $restored->getLines();
        $this->assertEquals($restoredLines[0]->item()->name(), $item->name());
        $this->assertTrue($restoredLines[0]->item()->id()->equals($item->id()));
        $this->assertTrue($restoredLines[0]->item()->price()->equals($item->price()));
    }
}

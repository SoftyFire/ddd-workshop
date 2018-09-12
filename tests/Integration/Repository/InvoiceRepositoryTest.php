<?php

namespace Billing\Tests\Integration;

use Billing\Domain\Entity\LineItem;
use Billing\Domain\Repository\InvoiceRepository;

/**
 * Class InvoiceRepositoryTest
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class InvoiceRepositoryTest extends TestCase
{
    /**
     * @var InvoiceRepository
     */
    protected $repo;

    protected function setUp()
    {
        parent::setUp();

        $this->repo = $this->container->get(InvoiceRepository::class);
    }

    public function testReadWrite()
    {
        $invoice = \Billing\Domain\Aggregate\Invoice::new();
        $item = \Billing\Domain\Aggregate\Item::new('Test item', new \Money\Money(10000, new \Money\Currency('USD')));
        $invoice->addLine(\Billing\Domain\Entity\LineItem::forItem($item));
        $this->repo->persist($invoice);


        $restored = $this->repo->get($invoice->id());

        $this->assertTrue($restored->id()->equals($invoice->id()));

        /** @var LineItem[] $restoredLines */
        $restoredLines = $restored->getLines();
        $this->assertEquals($restoredLines[0]->item()->name(), $item->name());
        $this->assertTrue($restoredLines[0]->item()->id()->equals($item->id()));
        $this->assertTrue($restoredLines[0]->item()->price()->equals($item->price()));
    }
}

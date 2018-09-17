<?php

namespace Billing\Tests\Integration;

use Billing\Domain\Aggregate\Customer;
use Billing\Domain\Entity\LineItem;
use Billing\Domain\Repository\CustomerRepository;
use Billing\Domain\Repository\InvoiceRepository;
use Billing\Domain\Value\EmailAddress;

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
    /**
     * @var CustomerRepository
     */
    protected $customerRepo;

    protected function setUp()
    {
        parent::setUp();

        $this->repo = $this->container->get(InvoiceRepository::class);
        $this->customerRepo = $this->container->get(CustomerRepository::class);
    }

    /**
     * // TODO: use fixtures
     *
     * @return Customer
     */
    private function getCustomer(): Customer
    {
        $email = EmailAddress::fromString('user@example.com');
        $customer = $this->customerRepo->findByEmail($email);

        if ($customer === null) {
            $customer = Customer::new($email);
            $this->customerRepo->persist($customer);
        }

        return $customer;
    }

    public function testReadWrite()
    {
        $customer = $this->getCustomer();

        $invoice = \Billing\Domain\Aggregate\Invoice::new($customer);
        $item = \Billing\Domain\Aggregate\Item::new('Test item', new \Money\Money(10000, new \Money\Currency('USD')));
        $invoice->addLine(\Billing\Domain\Entity\LineItem::forItem($item));
        $this->repo->persist($invoice);

        $restored = $this->repo->get($invoice->id());

        $this->assertTrue($restored->id()->equals($invoice->id()));
        $this->assertTrue($restored->customer()->id()->equals($customer->id()));

        /** @var LineItem[] $restoredLines */
        $restoredLines = $restored->getLines();
        $this->assertEquals($restoredLines[0]->item()->name(), $item->name());
        $this->assertTrue($restoredLines[0]->item()->id()->equals($item->id()));
        $this->assertTrue($restoredLines[0]->item()->price()->equals($item->price()));
    }
}

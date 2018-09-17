<?php

namespace Billing\Infrastructure\Repository;

use Billing\Domain\Aggregate\Customer;
use Billing\Domain\Repository\CustomerRepository;
use Billing\Domain\Value\EmailAddress;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\UuidInterface;

/**
 * Class DoctrineCustomerRepository
 */
class DoctrineCustomerRepository implements CustomerRepository
{
    /** @var ObjectManager */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function get(UuidInterface $uuid): Customer
    {
        $customer = $this->objectManager->find(Customer::class, $uuid->toString());

        if (!$customer instanceof Customer) {
            throw new \OutOfBoundsException(sprintf(
                'Customer "%s" does not exist',
                $uuid->toString()
            ));
        }

        return $customer;
    }

    public function persist(Customer $invoice) : void
    {
        $this->objectManager->persist($invoice);
        $this->objectManager->flush();
    }

    public function findByEmail(EmailAddress $email): ?Customer
    {
        return $this->objectManager->getRepository(Customer::class)->findOneBy([
            'email' => $email->toString()
        ]);
    }
}

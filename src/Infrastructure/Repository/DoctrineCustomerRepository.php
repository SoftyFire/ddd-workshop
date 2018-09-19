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
        throw new \BadMethodCallException(sprintf('Method "%s" is not implemented yet', __METHOD__));
    }

    public function persist(Customer $customer) : void
    {
        $this->objectManager->persist($customer);
        $this->objectManager->flush();
    }

    public function findByEmail(EmailAddress $email): ?Customer
    {
        $repo = $this->objectManager->getRepository(Customer::class);

        return $repo->findOneBy([
            'email' => $email->toString()
        ]);
    }
}

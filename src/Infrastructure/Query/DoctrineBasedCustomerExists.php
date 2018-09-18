<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Query;

use Billing\Domain\Aggregate\Customer;
use Billing\Domain\Value\EmailAddress;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineBasedCustomerExists implements \Billing\Domain\Query\CustomerExists
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(EmailAddress $address): bool
    {
        $customer = Customer::class;

        return (bool) $this->entityManager
            ->createQuery("
                SELECT  count(e) 
                FROM    $customer e
                WHERE   e.email = :email
            ")
            ->setParameter('email', $address, EmailAddress::class)
            ->getSingleScalarResult();
    }
}

<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Query;

use Billing\Domain\Aggregate\Customer;
use Billing\Domain\Query\CustomerExists;
use Billing\Domain\Value\EmailAddress;
use Doctrine\ORM\EntityManagerInterface;

class CustomerExistsWithDoctrine implements CustomerExists
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(EmailAddress $emailAddress): bool
    {
        $customer = Customer::class;

        return !(bool)$this->entityManager->createQuery("
            SELECT count(c)
            FROM   $customer c
            WHERE  c.email = :email
        ")
        ->setParameter('email', $emailAddress, EmailAddress::class)
        ->getSingleScalarResult();
    }
}

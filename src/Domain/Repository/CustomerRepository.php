<?php

namespace Billing\Domain\Repository;

use Billing\Domain\Aggregate\Customer;
use Billing\Domain\Value\EmailAddress;
use Ramsey\Uuid\UuidInterface;

interface CustomerRepository
{
    public function get(UuidInterface $uuid): Customer;

    public function findByEmail(EmailAddress $email): ?Customer;

    public function persist(Customer $customer): void;
}

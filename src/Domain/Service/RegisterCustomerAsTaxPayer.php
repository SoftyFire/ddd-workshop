<?php

namespace Billing\Domain\Service;

use Billing\Domain\Aggregate\Customer;

interface RegisterCustomerAsTaxPayer
{
    public function __invoke(Customer $customer);

    public function rollback(Customer $customer);

    public function transactionally(Customer $customer, \Closure $closure);
}

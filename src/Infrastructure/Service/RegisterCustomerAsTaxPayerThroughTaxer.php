<?php

namespace Billing\Infrastructure\Service;

use Billing\Domain\Aggregate\Customer;
use Billing\Domain\Service\RegisterCustomerAsTaxPayer;

class RegisterCustomerAsTaxPayerThroughTaxer implements RegisterCustomerAsTaxPayer
{
    public function __invoke(Customer $customer)
    {
        error_log('Customer '. $customer->email()->toString() . ' was registered as tax payer');
    }

    public function rollback(Customer $customer)
    {
        error_log('Customer '. $customer->email()->toString() . ' was UNregistered as tax payer');
    }

    public function transactionally(Customer $customer, \Closure $closure)
    {
        $this->__invoke($customer);

        try {
            if (!$closure($customer)) {
                $this->rollback($customer);
            }
        } catch (\Throwable $e) {
            $this->rollback($customer);
        }
    }
}

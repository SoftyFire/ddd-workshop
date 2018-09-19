<?php

namespace Billing\Infrastructure\Service;

use Billing\Domain\Aggregate\Customer;
use Billing\Domain\Service\RegisterCustomerAsPensionFundPayer;
use Billing\Domain\Service\RegisterCustomerAsTaxPayer;

class RegisterCustomerAsPensionFundPayerThroughTaxer implements RegisterCustomerAsPensionFundPayer
{
    public function __invoke(Customer $customer)
    {
        error_log('Customer '. $customer->email()->toString() . ' was registered as pension fund payer');
    }

    public function rollback(Customer $customer)
    {
        error_log('Customer '. $customer->email()->toString() . ' was UNregistered as pension fund payer');
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

<?php

namespace Billing\Domain\Aggregate;

use Billing\Domain\Event\CustomerWasCreated;
use Billing\Domain\Query\CustomerExists;
use Billing\Domain\Service\RegisterCustomerAsPensionFundPayer;
use Billing\Domain\Service\RegisterCustomerAsTaxPayer;
use Billing\Domain\Value\EmailAddress;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Customer
{
    use EventsAwareTrait;

    /**
     * @var UuidInterface
     */
    private $id;
    /**
     * @var EmailAddress
     */
    private $email;

    private function __construct()
    {
    }

    public static function new(
        EmailAddress $email,
        CustomerExists $customerExists,
        RegisterCustomerAsTaxPayer $registerCustomerAsTaxPayer,
        RegisterCustomerAsPensionFundPayer $registerCustomerAsPensionFundPayer
    ) {
        if ($customerExists($email)) {
            throw new \DomainException(sprintf('Customer with address "%s" already exists', $email->toString()));
        }

        $customer = new self();
        $customer->id = Uuid::uuid4();
        $customer->email = $email;
        $customer->recordThat(CustomerWasCreated::occurred($customer));

        $registerCustomerAsTaxPayer->transactionally($customer, function () use ($customer, $registerCustomerAsPensionFundPayer) {
             $registerCustomerAsPensionFundPayer($customer);
             return (bool)random_int(0, 1) ?: $registerCustomerAsPensionFundPayer->rollback($customer);
        });

        return $customer;
    }

    /**
     * @return UuidInterface
     */
    public function id(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return EmailAddress
     */
    public function email(): EmailAddress
    {
        return $this->email;
    }
}

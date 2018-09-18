<?php

namespace Billing\Domain\Aggregate;

use Billing\Domain\Query\CustomerExists;
use Billing\Domain\Value\EmailAddress;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Customer
{
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

    public static function new(EmailAddress $email, CustomerExists $customerExists)
    {
        if ($customerExists($email)) {
            throw new \DomainException(sprintf('Customer with address "%s" already exists', $email->toString()));
        }

        $customer = new self();
        $customer->id = Uuid::uuid4();
        $customer->email = $email;

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

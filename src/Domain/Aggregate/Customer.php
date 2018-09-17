<?php

namespace Billing\Domain\Aggregate;

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

    public static function new(EmailAddress $email)
    {
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

<?php

namespace Billing\Domain\Aggregate;

use Billing\Domain\Value\EmailAddress;
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

    public static function new(EmailAddress $email): self
    {
        throw new \BadMethodCallException(sprintf('Method "%s" is not implemented yet', __METHOD__));
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

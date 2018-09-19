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

    private function __construct(UuidInterface $id, EmailAddress $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public static function new(EmailAddress $email, CustomerExists $userExists): self
    {
        if (!$userExists($email)) {
            throw new \DomainException(sprintf(
                'User with address "%s" already exists',
                $email->toString()
            ));
        }

        return new self(
            Uuid::uuid4(),
            $email
        );
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

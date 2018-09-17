<?php

declare(strict_types=1);

namespace Billing\Domain\Value;

final class EmailAddress
{
    /**
     * @var string
     */
    private $email;

    private function __construct()
    {
    }

    public static function fromString(string $address): self
    {
        $email = new self;
        $email->ensureIsValid($address);
        $email->email = $address;

        return $email;
    }

    public function toString(): string
    {
        return $this->email;
    }

    private function ensureIsValid(string $email): void
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new \OutOfBoundsException(sprintf(
                'Email address %s is not valid',
                $email
            ));
        }
    }
}

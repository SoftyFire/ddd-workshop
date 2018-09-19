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
        if (!filter_var($address, \FILTER_VALIDATE_EMAIL)) {
            throw new \OutOfBoundsException(sprintf(
                'Email address %s is not valid',
                $address
            ));
        }

        $email = new self();
        $email->email = $address;

        return $email;
    }

    public function toString(): string
    {
        return $this->email;
    }
}

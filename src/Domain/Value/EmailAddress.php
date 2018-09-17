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
        throw new \BadMethodCallException(sprintf('Method "%s" is not implemented yet', __METHOD__));
    }

    public function toString(): string
    {
        return $this->email;
    }
}

<?php


namespace Billing\Domain\Event;

use Billing\Domain\Aggregate\Customer;

class CustomerWasCreated
{
    /**
     * @var Customer
     */
    private $target;

    private function __construct(Customer $target)
    {
        $this->target = $target;
    }

    public static function occurred(Customer $customer): self
    {
        return new self($customer);
    }

    public function target(): Customer
    {
        return $this->target;
    }
}

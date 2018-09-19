<?php

namespace Billing\Infrastructure\Command\Customer;

use Billing\Domain\Query\CustomerExists;
use Billing\Domain\Repository\CustomerRepository;
use Billing\Infrastructure\Event\EventStorage;

class CreateHandler
{
    /**
     * @var CustomerRepository
     */
    private $customerRepository;
    /**
     * @var CustomerExists
     */
    private $customerExists;
    /**
     * @var EventStorage
     */
    private $eventStorage;

    public function __construct(
        CustomerRepository $customerRepository,
        CustomerExists $customerExists,
        EventStorage $eventStorage
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerExists = $customerExists;
        $this->eventStorage = $eventStorage;
    }

    public function handle(CreateCommand $create)
    {
        $customer = \Billing\Domain\Aggregate\Customer::new(
            \Billing\Domain\Value\EmailAddress::fromString($create->email),
            $this->customerExists
        );

        $this->customerRepository->persist($customer);
        $this->eventStorage->store(...$customer->releaseEvents());

        return $customer;
    }
}

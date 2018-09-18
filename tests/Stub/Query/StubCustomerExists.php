<?php

declare(strict_types=1);

namespace Billing\Tests\Stub\Query;

use Billing\Domain\Query\CustomerExists;
use Billing\Domain\Value\EmailAddress;

class StubCustomerExists implements CustomerExists
{
    /**
     * @var bool
     */
    private $expectedResult;

    public function __construct(bool $expectedResult = false)
    {
        $this->expectedResult = $expectedResult;
    }

    public function __invoke(EmailAddress $address): bool
    {
        return $this->expectedResult;
    }
}

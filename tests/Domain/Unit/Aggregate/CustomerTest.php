<?php

namespace Billing\Tests\Domain\Unit\Aggregate;

use Billing\Domain\Aggregate\Customer;
use Billing\Domain\Query\CustomerExists;
use Billing\Domain\Value\EmailAddress;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testCustomerUniqnessIsChecked()
    {
        $emailAddress = EmailAddress::fromString('test@example.com');
        $this->expectException(\DomainException::class);

        Customer::new($emailAddress, new class implements CustomerExists {
            public function __invoke(EmailAddress $emailAddress): bool
            {
                return false;
            }
        });
    }
}

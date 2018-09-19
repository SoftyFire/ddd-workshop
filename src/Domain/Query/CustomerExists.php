<?php

declare(strict_types=1);

namespace Billing\Domain\Query;

use Billing\Domain\Value\EmailAddress;

interface CustomerExists
{
    public function __invoke(EmailAddress $emailAddress): bool;
}

<?php

namespace Billing\Infrastructure\Command\Customer;

use Billing\Infrastructure\Command\AbstractCommand;

class CreateCommand extends AbstractCommand
{
    public $email;


    public function validate(): void
    {
        if (empty($this->email)) {
            throw new \InvalidArgumentException('Email is required');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf(
                'Email "%s" is not valid',
                $this->email
            ));
        }
    }
}

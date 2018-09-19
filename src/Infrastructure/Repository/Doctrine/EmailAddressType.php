<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Repository\Doctrine;

use Billing\Domain\Value\EmailAddress;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EmailAddressType extends Type
{
    const NAME = 'email';

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param array $fieldDeclaration The field declaration.
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL([
            'length' => 128
        ]);
    }

    /**
     * Gets the name of this type.
     *
     * @return string
     *
     * @todo Needed?
     */
    public function getName()
    {
        return self::NAME;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof EmailAddress) {
            return $value->toString();
        }

        if (is_string($value)) {
            return $value;
        }

        throw new \InvalidArgumentException('Bad input type at ' . __METHOD__);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof EmailAddress) {
            return $value;
        }

        return EmailAddress::fromString($value);
    }
}

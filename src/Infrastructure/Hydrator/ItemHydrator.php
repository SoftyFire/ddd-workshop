<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Hydrator;

use Billing\Domain\Aggregate\Item;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\Uuid;

/**
 * Class ItemHydrator
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ItemHydrator extends BaseHydrator
{
    public function hydrate(array $data, $object)
    {
        $data['id'] = Uuid::fromString($data['id']);
        $data['price'] = new Money($data['price']['amount'], new Currency($data['price']['currencyCode']));

        return parent::hydrate($data, $object);
    }

    /**
     * Extract values from an object
     *
     * @param  Item $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'id' => $object->id(),
            'name' => $object->name(),
            'price' => [
                'amount' => $object->price()->getAmount(),
                'currencyCode' => $object->price()->getCurrency()->getCode(),
            ],
        ];
    }
}


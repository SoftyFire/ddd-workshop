<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Hydrator;

use Billing\Domain\Aggregate\Item;

/**
 * Class ItemHydrator
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ItemHydrator extends BaseHydrator
{
    public function hydrate(array $data, $object)
    {
        // TODO: implement
        throw new \InvalidArgumentException('Method ' . __METHOD__ . ' is not implemented yet.');

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  Item $object
     * @return array
     */
    public function extract($object)
    {
        // TODO: implement
        throw new \InvalidArgumentException('Method ' . __METHOD__ . ' is not implemented yet.');

        return [];
    }
}


<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Hydrator;

use Billing\Domain\Entity\LineItem;

/**
 * Class LineItemHydrator
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class LineItemHydrator extends BaseHydrator
{
    /**
     * @var ItemHydrator
     */
    private $itemHydrator;

    public function __construct(ItemHydrator $itemHydrator)
    {
        $this->itemHydrator = $itemHydrator;
    }

    public function hydrate(array $data, $classNameOrObject)
    {
        // TODO: implement
        throw new \InvalidArgumentException('Method ' . __METHOD__ . ' is not implemented yet.');

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Billing\Domain\Entity\LineItem $object
     * @return array
     */
    public function extract($object)
    {
        // TODO: implement
        throw new \InvalidArgumentException('Method ' . __METHOD__ . ' is not implemented yet.');

        return [];
    }
}

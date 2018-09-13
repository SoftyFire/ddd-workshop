<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Hydrator;

use Billing\Domain\Aggregate\Item;
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
        $data['item'] = $this->itemHydrator->hydrate($data['item'], Item::class);

        return parent::hydrate($data, $classNameOrObject);
    }

    /**
     * Extract values from an object
     *
     * @param  \Billing\Domain\Entity\LineItem $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'item' => $this->itemHydrator->extract($object->item()),
            'quantity' => $object->quantity(),
        ];
    }
}

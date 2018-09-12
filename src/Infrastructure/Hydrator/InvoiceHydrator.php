<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Hydrator;

use Billing\Domain\Aggregate\Invoice;

/**
 * Class InvoiceHydrator
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class InvoiceHydrator extends BaseHydrator
{
    /**
     * @var LineItemHydrator
     */
    private $lineItemHydrator;

    public function __construct(LineItemHydrator $lineItemHydrator)
    {
        $this->lineItemHydrator = $lineItemHydrator;
    }

    public function hydrate(array $data, $classNameOrObject)
    {
        // TODO: hydrate object
        throw new \InvalidArgumentException('Method ' . __METHOD__ . ' is not implemented yet.');

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  Invoice $object
     * @return array
     */
    public function extract($object)
    {
        throw new \InvalidArgumentException('Method ' . __METHOD__ . ' is not implemented yet.');

        return [
            // TODO: extract data from object
        ];
    }
}

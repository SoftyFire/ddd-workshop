<?php

namespace Billing\Infrastructure\Command;

use Psr\Http\Message\ServerRequestInterface;
use ReflectionProperty;

abstract class AbstractCommand implements CommandInterface
{
    public function attributes(): array
    {
        $reflection = new \ReflectionObject($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        return array_map(function (ReflectionProperty $property) {
            return $property->getName();
        }, $properties);
    }

    public function loadFromServerRequest(ServerRequestInterface $request): void
    {
        $data = $request->getParsedBody() ?: $request->getQueryParams();

        foreach ($this->attributes() as $attribute) {
            if (isset($data[$attribute])) {
                $this->$attribute = $data[$attribute];
            }
        }
    }
}


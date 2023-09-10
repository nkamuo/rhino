<?php

namespace App\GraphQL\Shipment\Resolver;


use Overblog\GraphQLBundle\Definition\Resolver\QueryInterface;

class ShipmentResolver implements QueryInterface
{
    public function shipments($name)
    {
        return sprintf('hello %s!!!', $name);
    }
}

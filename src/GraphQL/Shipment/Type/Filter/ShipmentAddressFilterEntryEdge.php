<?php
namespace App\GraphQL\Shipment\Type\Filter;

use Overblog\GraphQLBundle\Relay\Connection\Output\Edge;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Edge(
    node: 'ShipmentAddressFilterEntry',
)]
class ShipmentAddressFilterEntryEdge extends Edge
{
}

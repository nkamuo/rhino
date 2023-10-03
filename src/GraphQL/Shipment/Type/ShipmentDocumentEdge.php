<?php
namespace App\GraphQL\Shipment\Type;

use Overblog\GraphQLBundle\Relay\Connection\Output\Edge;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Edge(
    node: 'ShipmentDocument',
)]
class ShipmentDocumentEdge extends Edge
{
}

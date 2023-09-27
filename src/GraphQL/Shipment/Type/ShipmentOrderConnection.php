<?php
namespace App\GraphQL\Shipment\Type;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Connection(
    edge: 'ShipmentOrderEdge',
)]
class ShipmentOrderConnection extends Connection{

}
<?php
namespace App\GraphQL\Shipment\Type\Filter;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Relay\Connection(
    edge: 'ShipmentAddressFilterEntryEdge',
)]
class ShipmentAddressFilterEntryConnection extends Connection{

}
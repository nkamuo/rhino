<?php
namespace App\GraphQL\Shipment\Type\Filter;

use Overblog\GraphQLBundle\Annotation as GQL;


#[GQL\Enum()]
enum ShipmentAddressSequence: string{
    case ORIGIN = 'ORIGIN';
    case DESTINATION = 'DESTINATION';
}
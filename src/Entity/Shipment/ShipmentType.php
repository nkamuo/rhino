<?php
namespace App\Entity\Shipment;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum ShipmentType: string{
    case FTL = 'FTL';
    case LTL = 'LTL';
}
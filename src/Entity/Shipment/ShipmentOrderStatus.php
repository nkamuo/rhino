<?php
namespace App\Entity\Shipment;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum ShipmentOrderStatus: string{
    case PENDING = 'PENDING';
    case PUBLISHED = 'PUBLISHED';
    case CANCELLED = 'CANCELLED';
    case INTRANSIT = 'INTRANSIT';
    case COMPLETED = 'COMPLETED';
}
<?php

namespace App\Entity\Shipment;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum ShipmentOrderStatus: string
{
    case PENDING = 'PENDING';
    case CANCELLED = 'CANCELLED';
    case PROCESSING = 'PROCESSING';
    case INTRANSIT = 'INTRANSIT';
    case DELIVERED = 'DELIVERED';
    case COMPLETED = 'COMPLETED';
}

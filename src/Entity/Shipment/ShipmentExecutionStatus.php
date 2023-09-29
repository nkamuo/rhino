<?php

namespace App\Entity\Shipment;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum ShipmentExecutionStatus: string
{
    case PENDING = 'PENDING';
    case CANCELLED = 'CANCELLED';
    case PROCESSING = 'PROCESSING';
    case INTRANSIT = 'INTRANSIT';
    case COMPLETED = 'COMPLETED';
}

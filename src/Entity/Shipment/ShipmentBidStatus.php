<?php

namespace App\Entity\Shipment;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum ShipmentBidStatus: string
{
    case PENDING = 'PENDING';
    case INPROGRESS = 'INPROGRESS';
    case ACCEPTED = 'ACCEPTED';
    case WITHDRAWN = 'WITHDRAWN';
    case REJECTED = 'REJECTED';
    case COMPLETED = 'COMPLETED';
}

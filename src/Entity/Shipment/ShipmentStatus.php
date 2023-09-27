<?php
namespace App\Entity\Shipment;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum ShipmentStatus: string{
    case PENDING = 'PENDING';
    case PUBLISHED = 'PUBLISHED';
    case CANCELLED = 'CANCELLED';
    case PROCESSING = 'PROCESSING';
    case ACTIVE = 'ACTIVE';
    case COMPLETED = 'COMPLETED';
}
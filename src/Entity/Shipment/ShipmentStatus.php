<?php
namespace App\Entity\Shipment;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum ShipmentStatus: string{
    case PENDING = 'PENDING';
    case PUBLISHED = 'PUBLISHED';
    case CANCELLED = 'CANCELLED';
    case ACTIVE = 'ACTIVE';
    case COMPLETED = 'COMPLETED';
}
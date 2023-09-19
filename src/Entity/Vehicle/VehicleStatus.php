<?php

namespace App\Entity\Vehicle;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum VehicleStatus: string
{
    case PENDING = 'PENDING';
    case ACTIVE = 'ACTIVE';
    case DISABLED = 'DISABLED';
    /**
     * THis is used by admin to make a specific vehicle unavailable and the driver can't change it themself
     */
    case SUSPENDED = 'SUSPENDED';
}

<?php

namespace App\Entity\Account;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum DriverStatus: string
{
    case PENDING = 'pending';
    case VERIFYING = 'verifying';
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case REJECTED = 'rejected';
}

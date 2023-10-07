<?php

namespace App\Entity\Document;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum DriverLicenseStatus: string
{
    case PENDING = 'PENDING';
    case VERIFIED = 'VERIFIED';
    case REJECTED = 'REJECTED';
}

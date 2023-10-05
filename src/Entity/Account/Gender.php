<?php

namespace App\Entity\Account;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum Gender: string
{
    case MALE = 'MALE';
    case FEMALE = 'FEMALE';
    case NONBINARY = 'NONBINARY';
    case UNSPECIFIED = 'UNSPECIFIED';
}

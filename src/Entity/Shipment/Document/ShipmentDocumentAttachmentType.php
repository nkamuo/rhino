<?php

namespace App\Entity\Shipment\Document;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum ShipmentDocumentAttachmentType: string
{
    case IMAGE = 'image';
    case SIGNATURE = 'signature';
}

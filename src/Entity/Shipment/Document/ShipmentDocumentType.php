<?php

namespace App\Entity\Shipment\Document;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Enum()]
enum ShipmentDocumentType: string
{
    case PUC = 'pickup_confirmation';
    case POD = 'proof_of_delivery';
}

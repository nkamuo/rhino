<?php

namespace App\GraphQL\Shipment\Input;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Input()]
class AdminShipmentCreationInput extends ShipmentCreationInput
{

    #[GQL\Field(type: "Ulid!")]
    public Ulid $ownerId;
}

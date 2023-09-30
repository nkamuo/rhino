<?php

namespace App\GraphQL\Shipment\Input;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class ShipmentOrderEstimatedNodeArrivalInput
{

    #[GQL\Field(type: 'DateTimeImmutable!')]
    public \DateTimeInterface $datetime;

    #[GQL\Field()]
    public ?string $label = null;

    #[GQL\Field()]
    public ?string $description = null;
}

<?php

namespace App\GraphQL\Shipment\Input\Assessment;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Uid\Ulid;

#[GQL\Input]
class ShipmentOrderUnitReviewInput
{

    #[GQL\Field(type:'Ulid!')]
    public Ulid $parameterId;

    #[Assert\LessThanOrEqual(5)]
    #[GQL\Field()]
    public int $rating;

    #[GQL\Field()]
    public ?string $description = null;

    // #[GQL\Field(type: 'Json')]
    // public array $meta;
}

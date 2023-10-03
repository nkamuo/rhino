<?php

namespace App\GraphQL\Shipment\Input\Assessment;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input]
class ShipmentOrderReviewInput
{

    #[GQL\Field()]
    public ?string $description = null;

    /**
     * @var ShipmentOrderUnitReviewInput[]
     */
    #[Assert\Count(min: 1)]
    #[GQL\Field()]
    public array $unitReviews;

    // #[GQL\Field(type: 'Json')]
    // public array $meta = [];
}

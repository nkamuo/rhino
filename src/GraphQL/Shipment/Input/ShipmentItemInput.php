<?php

namespace App\GraphQL\Shipment\Input;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;


#[GQL\Input()]
class ShipmentItemInput
{
    #[GQL\Field(type: "Ulid")]
    public Ulid $productId;

    #[GQL\Field()]
    public int $quantity;

    #[GQL\Field()]
    public ?string $description = null;
}

<?php
namespace App\GraphQL\Shipment\Input;

use App\Entity\Shipment\ShipmentType;
use Symfony\Component\Uid\Ulid;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input()]
class ShipmentInput
{

    #[GQL\Field()]
    public ShipmentType $type;

    #[GQL\Field(type: 'Ulid')]
    public Ulid $originAddressId;

    #[GQL\Field(type: 'Ulid')]
    public Ulid $destinationAddressId;

    #[GQL\Field(type: 'Ulid')]
    public Ulid $billingAddressId;
    /**
     * @var ShipmentItemInput[]
     */
    #[GQL\Field()]
    public array $items;

    #[GQL\Field()]
    public ?ShipmentBugetInput $budget;
}

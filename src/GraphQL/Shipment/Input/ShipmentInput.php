<?php
namespace App\GraphQL\Shipment\Input;

use App\Entity\Shipment\ShipmentType;
use Symfony\Component\Uid\Ulid;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
abstract class ShipmentInput
{

    #[GQL\Field()]
    public ShipmentType $type;

    #[GQL\Field(type: 'Ulid!')]
    public Ulid $originAddressId;

    #[GQL\Field(type: 'Ulid!')]
    public Ulid $destinationAddressId;

    #[GQL\Field(type: 'Ulid!')]
    public Ulid $billingAddressId;
    /**
     * @var ShipmentItemInput[]
     */
    #[Assert\Count(min: 1, max: 50)]
    #[GQL\Field()]
    public array $items;

    #[GQL\Field()]
    public ?ShipmentBugetInput $budget;
}

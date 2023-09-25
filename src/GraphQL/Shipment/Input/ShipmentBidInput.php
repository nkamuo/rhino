<?php

namespace App\GraphQL\Shipment\Input;

use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;

#[GQL\Input()]
class ShipmentBidInput
{

    #[GQL\Field(type: 'Ulid!')]
    public Ulid $shipmentId;

    #[GQL\Field(type: 'Ulid!')]
    public Ulid $vehicleId;

    #[GQL\Field()]
    public ?ShipmentDriverBidInput $price = null;


    #[GQL\Field(type: 'DateTimeImmutable')]
    public ?\DateTimeInterface $pickupAt = null;


    #[GQL\Field(type: 'DateTimeImmutable')]
    public ?\DateTimeInterface $deliveryAt = null;

    #[GQL\Field()]
    public ?string $title = null;

    #[GQL\Field()]
    public ?string $description = null;
}

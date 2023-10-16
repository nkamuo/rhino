<?php

namespace App\GraphQL\Vehicle\Input;

use App\Entity\Vehicle\Vehicle;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Input()]
class VehicleInput
{
    #[GQL\Field()]
    public string $label;

    #[GQL\Field()]
    public string $vin;


    #[GQL\Field()]
    public string $licensePlateNumber;

    #[GQL\Field()]
    public ?string $description = null;


    #[GQL\Field()]
    public int $maxWeightCapacity;

    #[GQL\Field()]
    public VehicleDimensionInput $dimension;

    #[GQL\Field(type: "Ulid!")]
    public Ulid $vehicleTypeId;

    public function build(Vehicle $vehicle): void
    {
        $vehicle
            ->setLabel($this->label)
            ->setVin($this->vin)
            ->setLicensePlateNumber($this->licensePlateNumber)
            ->setMaxWeightCapacity($this->maxWeightCapacity)
            ->setDimension($this->dimension?->toInstance())
            ->setDescription($this->description);
    }
}

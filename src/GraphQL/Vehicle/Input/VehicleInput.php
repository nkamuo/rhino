<?php

namespace App\GraphQL\Vehicle\Input;

use App\Entity\Vehicle\Vehicle;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Input()]
class VehicleInput
{
    #[GQL\Field()]
    public string $title;

    #[GQL\Field()]
    public ?string $description;

    
    #[GQL\Field()]
    public int $weight;

    #[GQL\Field()]
    public VehicleDimensionInput $dimension;
    
    #[GQL\Field(type: "Ulid!")]
    public Ulid $vehicleTypeId;

    public function build(Vehicle $product): void
    {
        $product
            // ->setTitle($this->title)
            // ->setDescription($this->description)
            // ->setWeight($this->weight)
            // ->setPrice($this->price?->toInstance())
            // ->setDimension($this->dimension?->toInstance())
            ;
    }
}

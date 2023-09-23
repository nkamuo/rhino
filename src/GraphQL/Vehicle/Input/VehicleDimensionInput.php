<?php

namespace App\GraphQL\Vehicle\Input;

use App\Entity\Vehicle\VehicleDimension;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input()]
class VehicleDimensionInput
{
    #[GQL\Field()]
    public int $length;

    #[GQL\Field()]
    public int $width;

    #[GQL\Field()]
    public int $height;

    #[GQL\Field()]
    public string $unit;


    public function toInstance(): VehicleDimension
    {
        $dimension = new VehicleDimension();
        $dimension
            ->setLength($this->length)
            ->setWidth($this->width)
            ->setHeight($this->height)
            ->setUnit($this->unit);
        return $dimension;
    }
}

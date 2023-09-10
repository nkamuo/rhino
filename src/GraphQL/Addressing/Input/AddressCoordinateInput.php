<?php

namespace App\GraphQL\Addressing\Input;

use App\Entity\Addressing\Coordinate;
use Overblog\GraphQLBundle\Annotation as GQL;


#[GQL\Input()]
class AddressCoordinateInput
{

    #[GQL\Field()]
    public float $latitude;

    #[GQL\Field()]
    public float $longitude;


    #[GQL\Field()]
    public ?float $altitude = null;

    #[GQL\Field()]
    public ?float $accuracy = null;

    public function toInstance(): Coordinate{
        $coordinate = new Coordinate();
        $coordinate
            ->setLatitude($this->latitude)
            ->setLongitude($this->longitude)
            ->setAltitude($this->altitude)
            ->setAccuracy($this->accuracy)
            ;
        return $coordinate;
    }
}

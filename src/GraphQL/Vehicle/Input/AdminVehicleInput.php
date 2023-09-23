<?php
namespace App\GraphQL\Vehicle\Input;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Input()]
class AdminVehicleInput extends VehicleInput{


}
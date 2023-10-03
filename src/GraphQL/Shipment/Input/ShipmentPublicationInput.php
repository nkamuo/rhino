<?php
namespace App\GraphQL\Shipment\Input;

use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Input()]
class ShipmentPublicationInput
{
    #[GQL\Field()]
    public ?ShipmentBugetInput $budget = null;
}

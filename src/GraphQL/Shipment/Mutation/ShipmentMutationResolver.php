<?php
namespace App\GraphQL\Shipment\Mutation;

use App\Entity\Shipment\Shipment;
use App\GraphQL\Shipment\Input\ShipmentInput;
use App\Repository\Shipment\ShipmentRepository;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Provider()]
class ShipmentMutationResolver
{
    

    public function __construct(
        private ShipmentRepository $shipmentRepository,
    ){

    }

    #[GQL\Mutation()]
    public function createNewShipment(ShipmentInput $input): Shipment{
        $shipment = new Shipment();

        return $shipment;
    }
}

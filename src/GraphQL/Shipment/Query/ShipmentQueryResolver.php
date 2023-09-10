<?php

namespace App\GraphQL\Shipment\Query;


use App\Entity\Shipment\Shipment;
use App\GraphQL\Shipment\Type\ShipmentConnection;
use App\GraphQL\Shipment\Type\ShipmentEdge;
use App\Repository\Shipment\ShipmentRepository;
use App\Util\Doctrine\QueryBuilderHelper;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider()]
class ShipmentQueryResolver
{

    public function __construct(
        private ShipmentRepository $shipmentRepository,
        private Security $security,
    ) {
    }



    #[Query(name: "get_shipment_item",)]
    #[GQL\Arg(
        name: 'name',
        type: 'Ulid'
    )]
    public function getShipmentItem(
        #[GQL\Arg(type: 'Ulid')] Ulid $id
    ): Shipment {

        $shipment = $this->shipmentRepository->find($id);
        if ($shipment === null) {
            throw new UserError(
                message: "Cannot find shipment with [id:$id]"
            );
        }

        if (!$this->security->isGranted('view', $shipment)) {
            throw new UserError(
                message: "Permision Denied: You may not view this resource"
            );
        }



        return $shipment;
    }

    #[GQL\Query(name: "get_shipment_list")]
    public function getShipmentConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
    ): ShipmentConnection {


        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ShipmentConnection($edges, $pageInfo),
            fn (string $coursor, Shipment $brand, int $index) => new ShipmentEdge($coursor, $brand)
        );

        $qb = $this->shipmentRepository->createQueryBuilder('shipment');
        QueryBuilderHelper::applyCriteria($qb, $filter, 'shipment');

        $total = fn () => (int) (clone $qb)->select('COUNT(shipment.id)')->getQuery()->getSingleScalarResult();
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($qb) {
            return $qb->getQuery()->getResult();
        }, false, $cb);

        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }
}

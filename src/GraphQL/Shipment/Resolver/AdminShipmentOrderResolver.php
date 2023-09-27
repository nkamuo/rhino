<?php

namespace App\GraphQL\Shipment\Resolver;

use App\Entity\Account\User;
use App\Entity\Shipment\ShipmentOrder;
use App\Entity\Shipment\ShipmentOrderBidStatus;
use App\Entity\Shipment\ShipmentOrderDriverBid;
use App\Entity\Vehicle\Vehicle;
use App\GraphQL\Shipment\Input\ShipmentOrderBidCreationInput;
use App\GraphQL\Shipment\Type\ShipmentOrderConnection;
use App\GraphQL\Shipment\Type\ShipmentOrderDriverBidConnection;
use App\GraphQL\Shipment\Type\ShipmentOrderDriverBidEdge;
use App\GraphQL\Shipment\Type\ShipmentOrderEdge;
use App\Repository\Shipment\ShipmentOrderDriverBidRepository;
use App\Repository\Shipment\ShipmentOrderRepository;
use App\Repository\Vehicle\VehicleRepository;
use App\Util\Doctrine\QueryBuilderHelper;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider(
    targetQueryTypes: ['AdminQuery'],
    targetMutationTypes: ['AdminMutation'],
)]
class AdminShipmentOrderResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ShipmentOrderRepository $shipmentOrderRepository,
        private VehicleRepository $vehicleRepository,
        private Security $security,
    ) {
    }


    #[Query(name: "get_shipment_order_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid'
    )]
    public function getShipmentOrderItem(
        #[GQL\Arg(type: 'Ulid')] Ulid $id
    ): ShipmentOrder {

        $shipment = $this->shipmentOrderRepository->find($id);
        if ($shipment === null) {
            throw new UserError(
                message: "Cannot find shipment with [id:$id]"
            );
        }

        // if (!$this->security->isGranted('view', $shipment)) {
        //     throw new UserError(
        //         message: "Permision Denied: You may not view this resource"
        //     );
        // }
        return $shipment;
    }


    #[GQL\Query(name: "get_shipment_order_list")]
    // #[GQL\Access("isGranted('ROLE_USER')")]
    public function getShipmentOrderConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): ShipmentOrderConnection {

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ShipmentOrderConnection($edges, $pageInfo),
            fn (string $coursor, ShipmentOrder $order, int $index) => new ShipmentOrderEdge($coursor, $order)
        );

        $qb = $this->shipmentOrderRepository->createQueryBuilder('_order');
        QueryBuilderHelper::applyCriteria($qb, $filter, '_order');

        $total = fn () => (int) (clone $qb)->select('COUNT(_order.id)')->getQuery()->getSingleScalarResult();
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($qb) {
            return $qb
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }, false, $cb);

        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }




    private function getUser(): User
    {
        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }
        return $user;
    }
    private function getDriver()
    {
        $user = $this->getUser();
        $driver = $user->getDriver();
        if (!$driver) {
            throw new UserError("Could not find the driver account associated with your profile");
        }
        return $driver;
    }


    private function getShipmentOrderById(Ulid $id): ShipmentOrder{
        $shipment = $this->shipmentOrderRepository->find($id);
        if(null == $shipment){
            throw new UserError("Cannot find shipment with [id: {$id}]");
        }
        return $shipment;
    }
}

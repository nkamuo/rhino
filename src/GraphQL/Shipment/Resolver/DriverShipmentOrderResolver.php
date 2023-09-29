<?php

namespace App\GraphQL\Shipment\Resolver;

use App\Entity\Account\User;
use App\Entity\Shipment\ShipmentExecution;
use App\Entity\Shipment\ShipmentExecutionStatus;
use App\Entity\Shipment\ShipmentOrder;
use App\Entity\Shipment\ShipmentOrderOrderStatus;
use App\Entity\Shipment\ShipmentOrderStatus;
use App\Entity\Vehicle\Vehicle;
use App\GraphQL\Shipment\Input\ShipmentOrderBidCreationInput;
use App\GraphQL\Shipment\Type\ShipmentOrderConnection;
use App\GraphQL\Shipment\Type\ShipmentOrderDriverBidConnection;
use App\GraphQL\Shipment\Type\ShipmentOrderDriverBidEdge;
use App\GraphQL\Shipment\Type\ShipmentOrderEdge;
use App\Repository\Shipment\ShipmentOrderDriverBidRepository;
use App\Repository\Shipment\ShipmentOrderRepository;
use App\Repository\Vehicle\VehicleRepository;
use App\Service\Identity\CodeGeneratorInterface;
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
    targetQueryTypes: ['DriverQuery'],
    targetMutationTypes: ['DriverMutation'],
)]
class DriverShipmentOrderResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ShipmentOrderRepository $shipmentOrderRepository,
        private CodeGeneratorInterface $codeGenerator,
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

        $driver = $this->getDriver();

        $qb = $this->shipmentOrderRepository->createQueryBuilder('_order');

        
        $qb
            ->innerJoin('_order.driver', 'driver')
            ->andWhere("driver.id = :driver")
            ->setParameter("driver", $driver->getId(), UlidType::NAME);


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




    

    #[GQL\Mutation()]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    public function startShipmentOrder(Ulid $id): ShipmentOrder
    {
        $driver = $this->getDriver();
        $order = $this->getDriverShipmentOrder($id);
        $shipment = $order->getShipment();

        
        if (($status = $order->getExecution()) != null) {
            throw new UserError(sprintf("This shipment order execution is already \"%s\"", $status->getStatus()->value));
        }


        if ($order->getStatus() != ShipmentOrderStatus::PENDING) {
            throw new UserError("This order cannot be accepted");
        }

        $code = $this->codeGenerator->generateCode(length: 6);
        $execution = new ShipmentExecution();
        $execution
            ->setCode($code)
            ->setVehicle($order->getVehicle())
            ->setDriver($driver)
            ;

        $execution
            ->addOrder($order)
            ->setStatus(ShipmentExecutionStatus::PROCESSING)
            ;
        $order->setStatus(ShipmentOrderStatus::PROCESSING);

        $this->entityManager->persist($execution);
        $this->entityManager->flush();

        return $order;
    }


    
    private function getDriverShipmentOrder(Ulid $id): ShipmentOrder
    {
        $order = $this->shipmentOrderRepository->find($id);
        if (null == $order || $order->getDriver() != $this->getDriver()) {
            throw new UserError("Could not find shipment order with [id:{$id}] on your repository");
        }
        return $order;
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

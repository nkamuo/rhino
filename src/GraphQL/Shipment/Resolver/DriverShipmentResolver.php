<?php

namespace App\GraphQL\Shipment\Resolver;

use App\Entity\Account\User;
use App\Entity\Shipment\Shipment;
use App\Entity\Shipment\ShipmentBidStatus;
use App\Entity\Shipment\ShipmentDriverBid;
use App\Entity\Shipment\ShipmentStatus;
use App\Entity\Vehicle\Vehicle;
use App\GraphQL\Shipment\Input\ShipmentBidCreationInput;
use App\GraphQL\Shipment\Type\ShipmentConnection;
use App\GraphQL\Shipment\Type\ShipmentDriverBidConnection;
use App\GraphQL\Shipment\Type\ShipmentDriverBidEdge;
use App\GraphQL\Shipment\Type\ShipmentEdge;
use App\Repository\Shipment\ShipmentDriverBidRepository;
use App\Repository\Shipment\ShipmentRepository;
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
    targetQueryTypes: ['DriverQuery'],
    targetMutationTypes: ['DriverMutation'],
)]
class DriverShipmentResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ShipmentRepository $shipmentRepository,
        private ShipmentDriverBidRepository $shipmentDriverBidRepository,
        private VehicleRepository $vehicleRepository,
        private Security $security,
    ) {
    }


    #[Query(name: "get_load_item",)]
    #[GQL\Arg(
        name: 'id',
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

        // if (!$this->security->isGranted('view', $shipment)) {
        //     throw new UserError(
        //         message: "Permision Denied: You may not view this resource"
        //     );
        // }
        return $shipment;
    }


    #[GQL\Query(name: "get_load_list")]
    // #[GQL\Access("isGranted('ROLE_USER')")]
    public function getShipmentConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): ShipmentConnection {

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ShipmentConnection($edges, $pageInfo),
            fn (string $coursor, Shipment $shipment, int $index) => new ShipmentEdge($coursor, $shipment)
        );

        $qb = $this->shipmentRepository
            ->createQueryBuilder('shipment')
            ->andWhere('shipment.status = :status')
            ->setParameter('status',ShipmentStatus::PUBLISHED);


        QueryBuilderHelper::applyCriteria($qb, $filter, 'shipment');

        $total = fn () => (int) (clone $qb)->select('COUNT(shipment.id)')->getQuery()->getSingleScalarResult();
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($qb) {
            return $qb
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }, false, $cb);

        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }




    /////////////////////////////////////////
    // SHIPMENT BIDDING AND LISTING FROM HERE
    /////////////////////////////////


    #[Query(name: "get_load_bid_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid'
    )]
    public function getShipmentBidItem(
        #[GQL\Arg(type: 'Ulid')] Ulid $id
    ): ShipmentDriverBid {
        $bid = $this->getDriverShipmentBid($id);
        return $bid;
    }

    
    #[GQL\Query(name: "get_load_bid_list")]
    // #[GQL\Access("isGranted('ROLE_USER')")]
    public function getShipmentDriverBidConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): ShipmentDriverBidConnection {

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ShipmentDriverBidConnection($edges, $pageInfo),
            fn (string $coursor, ShipmentDriverBid $bid, int $index) => new ShipmentDriverBidEdge($coursor, $bid)
        );

        $qb = $this->shipmentDriverBidRepository->createQueryBuilder('bid');

        QueryBuilderHelper::applyCriteria($qb, $filter, 'bid');

        $driver = $this->getDriver();

        $qb
            ->innerJoin('bid.driver', 'driver')
            ->andWhere("driver.id = :driver")
            ->setParameter("driver", $driver->getId(), UlidType::NAME);

        $total = fn () => (int) (clone $qb)->select('COUNT(bid.id)')->getQuery()->getSingleScalarResult();

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
    public function createNewShipmentBid(ShipmentBidCreationInput $input): ShipmentDriverBid
    {
        $shipment = $this->getBiddableShipmentById($input->shipmentId);
        $driver = $this->getDriver();
        $vehicle =  $this->getDriverVehicleById($input->vehicleId);
        //
        // $code = $this->codeGenerator->generateCode(length: 8);
        $bid = new ShipmentDriverBid();
        $bid
            ->setDriver($driver)
            ->setVehicle($vehicle)
            ->setTitle($input->title)
            ->setDescription($input->description)
            ;

        if ($input->pickupAt) {
            $bid->setPickupAt(DateTimeImmutable::createFromInterface($input->pickupAt));
        }
        if ($input->deliveryAt) {
            $bid->setDeliveryAt(DateTimeImmutable::createFromInterface($input->deliveryAt));
        }

        if ($price = $input->price) {
            $bid->setPrice($price->toInstance());
        }

        $shipment->addBid($bid);

        $this->entityManager->persist($shipment);
        $this->entityManager->flush();

        return $bid;
    }

    #[GQL\Mutation]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    public function deleteShipmentBid(Ulid $id): ShipmentDriverBid
    {
        $bid = $this->getDriverShipmentBid($id);
        if (($status = $bid->getStatus()) != ShipmentBidStatus::PENDING) {
            throw new UserError(sprintf("You cannot delete a bid in the %s state", $status));
        }
        $this->entityManager->remove($bid);
        $this->entityManager->flush();
        return $bid;
    }

    #[GQL\Mutation]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    public function withdrawlShipmentBid(Ulid $id): ShipmentDriverBid
    {
        $bid = $this->getDriverShipmentBid($id);
        if (($status = $bid->getStatus()) != ShipmentBidStatus::PENDING) {
            throw new UserError(sprintf("You cannot withdaw a bid in the %s state", $status));
        }
        $bid->setStatus(ShipmentBidStatus::WITHDRAWN);
        $this->entityManager->persist($bid);
        $this->entityManager->flush();
        return $bid;
    }

    // NORMALLY, DRIVERS CAN ONLY CANCEL OR WITHDRAW AN ACTIVE/PENDINg BID
    // #[GQL\Mutation]
    // #[GQL\Arg(name: 'id', type: 'Ulid!')]
    // #[GQL\Arg(name: 'status', type: 'ShipmentBidStatus!')]
    // public function updateShipmentBidStatus(Ulid $id): ShipmentDriverBid
    // {
    //     $bid = $this->getDriverShipmentBid($id);
    //     if(($status = $bid->getStatus()) != ShipmentBidStatus::PENDING){
    //         throw new UserError(sprintf("You cannot delete a status ins the %s state", $status));
    //     }
    //     $this->entityManager->remove($bid);
    //     $this->entityManager->flush();
    //     return $bid;
    // }



    private function getDriverShipmentBid(Ulid $id): ShipmentDriverBid
    {
        $bid = $this->shipmentDriverBidRepository->find($id);
        if ($bid?->getDriver() != $this->getDriver()) {
            throw new UserError("Could  not find driver's bid with [id:{$id}]");
        }
        return $bid;
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
    private function getDriverVehicleById(Ulid $id): Vehicle
    {
        $driver = $this->getDriver();
        $vehicle = $this->vehicleRepository->find($id);

        if ($vehicle->getDriver() != $driver) {
            throw new UserError("Could not find driver vehicle with [id:{$id}]");
        }
        return $vehicle;
    }



    private function getBiddableShipmentById(Ulid $id): Shipment{
        $shipment = $this->getShipmentById($id);
        //TODO: Confirm that a bid  can be placed against this shipment otherwise throw
        return $shipment;
    }

    private function getShipmentById(Ulid $id): Shipment{
        $shipment = $this->shipmentRepository->find($id);
        if(null == $shipment){
            throw new UserError("Cannot find shipment with [id: {$id}]");
        }
        return $shipment;
    }
}

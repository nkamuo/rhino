<?php

namespace App\GraphQL\Shipment\Resolver;

use App\Entity\Account\User;
use App\Entity\Addressing\UserAddress;
use App\Entity\Catalog\UserProduct;
use App\Entity\Shipment\Shipment;
use App\Entity\Shipment\ShipmentItem;
use App\GraphQL\Shipment\Input\ShipmentCreationInput;
use App\GraphQL\Shipment\Input\ShipmentItemInput;
use App\GraphQL\Shipment\Type\ShipmentConnection;
use App\GraphQL\Shipment\Type\ShipmentEdge;
use App\Repository\Addressing\UserAddressRepository;
use App\Repository\Catalog\UserProductRepository;
use App\Repository\Shipment\ShipmentRepository;
use App\Service\Google\DirectionsServiceInterface;
use App\Util\Doctrine\QueryBuilderHelper;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Bridge\Doctrine\Types\UlidType;

#[GQL\Provider(
    targetQueryTypes: ['AdminQuery'],
    targetMutationTypes: ['AdminMutation']
)]
class AdminShipmentResolver
{


    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private ShipmentRepository $shipmentRepository,
        private UserAddressRepository $userAddressRepository,
        private UserProductRepository $userProductRepository,
        private DirectionsServiceInterface $directionsService,
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
    #[GQL\Access("isGranted('ROLE_ADMIN')")]
    public function getShipmentConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): ShipmentConnection {


        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ShipmentConnection($edges, $pageInfo),
            fn (string $coursor, Shipment $shipment, int $index) => new ShipmentEdge($coursor, $shipment)
        );

        $qb = $this->shipmentRepository->createQueryBuilder('shipment');

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


    #[GQL\Mutation()]
    #[GQL\Access("isGranted('ROLE_USER')")]
    public function createNewShipment(ShipmentCreationInput $input): Shipment
    {
        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }


        $billingAddress =  $this->getUserAddress($input->billingAddressId, $user);
        $originAddress = $this->getUserAddress($input->originAddressId, $user);
        $destinationAddress = $this->getUserAddress($input->destinationAddressId, $user);

        $shipment = new Shipment();

        $shipment
            ->setType($input->type)
            ->setBillingAddress($billingAddress)
            ->setOriginAddress($originAddress)
            ->setDestinationAddress($destinationAddress)
            ->setOwner($user);

        if ($input->pickupAt) {
            $shipment->setPickupAt(DateTimeImmutable::createFromInterface($input->pickupAt));
        }
        if ($input->deliveryAt) {
            $shipment->setDeliveryAt(DateTimeImmutable::createFromInterface($input->deliveryAt));
        }

        foreach ($input->items as $iInput) {
            $item = $this->buildShipmentItem($iInput, $user);
            $shipment->addItem($item);
        }

        $route = $this->directionsService->getRoute(
            origin: $originAddress,
            destination: $destinationAddress,
        );

        $shipment->setRoute($route);

        $this->entityManager->persist($shipment);
        $this->entityManager->flush();

        return $shipment;
    }


    private function buildShipmentItem(ShipmentItemInput $input, User $user): ShipmentItem
    {
        $item = new ShipmentItem();
        $product = $this->getUserProduct($input->productId, $user);
        $item
            ->setProduct($product)
            ->setQuantity($input->quantity)
            ->setDescription($input->description);
        return $item;
    }


    private function getUserProduct(Ulid $id, User $user): UserProduct
    {
        $address = $this->userProductRepository->find($id);
        if (null == $address || $address->getOwner() != $user) {
            throw new UserError("Could not find your product with [id:{$id}]");
        }
        return $address;
    }

    private function getUserAddress(Ulid $id, User $user): UserAddress
    {
        $address = $this->userAddressRepository->find($id);
        if (null == $address || $address->getOwner() != $user) {
            throw new UserError("Could not find your address with [id:{$id}]");
        }
        return $address;
    }
}

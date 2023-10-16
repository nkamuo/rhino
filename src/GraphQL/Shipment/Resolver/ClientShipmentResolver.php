<?php

namespace App\GraphQL\Shipment\Resolver;

use App\CQRS\CommandBusInterface;
use App\CQRS\QueryBusInterface;
use App\Entity\Account\User;
use App\Entity\Addressing\UserAddress;
use App\Entity\Catalog\UserProduct;
use App\Entity\Chat\AbstractChatChannel;
use App\Entity\Chat\ChatChannel;
use App\Entity\Chat\ChatSubject;
use App\Entity\Shipment\Assessment\AssessmentParameter;
use App\Entity\Shipment\Assessment\Review;
use App\Entity\Shipment\Assessment\UnitReview;
use App\Entity\Shipment\Shipment;
use App\Entity\Shipment\ShipmentBidStatus;
use App\Entity\Shipment\ShipmentDriverBid;
use App\Entity\Shipment\ShipmentItem;
use App\Entity\Shipment\ShipmentOrder;
use App\Entity\Shipment\ShipmentOrderStatus;
use App\Entity\Shipment\ShipmentStatus;
use App\GraphQL\Chat\Input\Message\ChatMessageCreationInput;
use App\GraphQL\Shipment\Input\Assessment\ShipmentOrderReviewInput;
use App\GraphQL\Shipment\Input\ShipmentCreationInput;
use App\GraphQL\Shipment\Input\ShipmentItemInput;
use App\GraphQL\Shipment\Input\ShipmentPublicationInput;
use App\GraphQL\Shipment\Type\ShipmentConnection;
use App\GraphQL\Shipment\Type\ShipmentDriverBidConnection;
use App\GraphQL\Shipment\Type\ShipmentDriverBidEdge;
use App\GraphQL\Shipment\Type\ShipmentEdge;
use App\Message\Account\CalculateDriverRating;
use App\Repository\Addressing\UserAddressRepository;
use App\Repository\Catalog\UserProductRepository;
use App\Repository\Shipment\Assessment\AssessmentParameterRepository;
use App\Repository\Shipment\ShipmentDriverBidRepository;
use App\Repository\Shipment\ShipmentRepository;
use App\Service\Chat\DirectMessageResolverInterface;
use App\Service\Google\DirectionsServiceInterface;
use App\Service\Identity\CodeGeneratorInterface;
use App\Util\Doctrine\QueryBuilderHelper;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
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
    targetQueryTypes: ['ClientQuery'],
    targetMutationTypes: ['ClientMutation']
)]
class ClientShipmentResolver
{


    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private ShipmentRepository $shipmentRepository,
        private UserAddressRepository $userAddressRepository,
        private UserProductRepository $userProductRepository,
        private ShipmentDriverBidRepository $shipmentDriverBidRepository,
        private AssessmentParameterRepository $assessmentParameterRepository,
        private DirectionsServiceInterface $directionsService,
        private CodeGeneratorInterface $codeGenerator,
        private QueryBusInterface $queryBus,
        private CommandBusInterface $commandBus,
        private DirectMessageResolverInterface $dmResolver,
    ) {
    }






    #[Query(name: "get_shipment_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    public function getShipmentItem(Ulid $id): Shipment
    {

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

    #[GQL\Query(name: "get_shipment_list")]
    // #[GQL\Access("isGranted('ROLE_USER')")]
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

        $qb
            ->innerJoin('shipment.owner', 'owner')
            ->andWhere("owner.id = :owner")
            ->setParameter("owner", $user->getId(), UlidType::NAME);


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

        $code = $this->codeGenerator->generateCode(length: 8);

        $shipment
            ->setCode($code)
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

        if ($budgetInput = $input->budget) {
            $shipment->setBudget($budgetInput->toInstance());
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







    #[GQL\Mutation()]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    #[GQL\Arg(name: 'input', type: 'ShipmentPublicationInput')]
    public function publishShipment(Ulid $id, ?ShipmentPublicationInput $input): Shipment
    {
        $user = $this->getUser();
        $shipment = $this->getUserShipment($id, $user);

        if ($shipment->getStatus() != ShipmentStatus::PENDING) {
            $message = sprintf(
                "Only \"%s\" Shipments can be published. Shipment is in the \"%s\" state",
                ShipmentStatus::PENDING->name,
                $shipment->getStatus()?->name
            );
            throw new UserError($message);
        }

        if ($budget = $input?->budget) {
            $shipment->setBudget($budget->toInstance());
        }
        $shipment->setStatus(ShipmentStatus::PUBLISHED);

        $this->entityManager->persist($shipment);
        $this->entityManager->flush();

        return $shipment;
    }




    #[GQL\Mutation()]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    #[GQL\Arg(name: 'input', type: 'ShipmentOrderReviewInput')]
    public function reviewShipment(Ulid $id, ?ShipmentOrderReviewInput $input): Shipment
    {
        $user = $this->getUser();
        $shipment = $this->getUserShipment($id, $user);
        $order = $shipment->getShipmentOrder();

        if ($order->getStatus() != ShipmentOrderStatus::DELIVERED) {
            $message = sprintf(
                "Only \"%s\" Shipment Orders can be reviewed. Shipment Order is in the \"%s\" state",
                ShipmentOrderStatus::DELIVERED->name,
                $order->getStatus()?->name
            );
            throw new UserError($message);
        }

        if ($shipment->getStatus() != ShipmentStatus::DELIVERED) {
            $message = sprintf(
                "Only \"%s\" Shipments can be reviewed. Shipment is in the \"%s\" state",
                ShipmentStatus::DELIVERED->name,
                $shipment->getStatus()?->name
            );
            throw new UserError($message);
        }

        $review = new Review();
        $review
            ->setReviewer($user)
            ->setDescription($input->description);


        $parameters = new ArrayCollection();

        foreach ($input->unitReviews as $uRevInputs) {
            $unitReview = new UnitReview();
            $parameter = $this->getAssessmentParameter($uRevInputs->parameterId);
            if ($parameters->contains($parameter)) {
                throw new UserError("Cannot review {$parameter->getTitle()} more than once");
            }
            $parameters->add($parameter);

            $unitReview
                ->setParameter($parameter)
                ->setRating($uRevInputs->rating)
                ->setDescription($uRevInputs->description);
            $review->addUnitReview($unitReview);
        }

        $order->setReview($review);
        $order->setStatus(ShipmentOrderStatus::COMPLETED);
        $shipment->setStatus(ShipmentStatus::COMPLETED);

        $this->entityManager->persist($shipment);
        $this->entityManager->flush();

        $driver = $order->getDriver();
        $this->commandBus->dispatch(new CalculateDriverRating($driver));

        return $shipment;
    }



    private function getAssessmentParameter(Ulid $id): AssessmentParameter
    {
        $parameter = $this->assessmentParameterRepository->find($id);
        if (null == $parameter) {
            throw new UserError("Could not find Shipment assessment parameter with [id:{$id}]");
        }
        return $parameter;
    }




    /////////////////////////////////////////
    // SHIPMENT BIDDING AND LISTING FROM HERE
    /////////////////////////////////


    #[Query(name: "get_shipment_bid_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid'
    )]
    public function getShipmentBidItem(
        #[GQL\Arg(type: 'Ulid')] Ulid $id
    ): ShipmentDriverBid {
        $bid = $this->getUserShipmentBid($id);
        return $bid;
    }


    #[GQL\Query(name: "get_shipment_bid_list")]
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

        $user = $this->getUser();

        $qb
            ->innerJoin('bid.shipment', 'shipment')
            ->innerJoin('shipment.owner', 'owner')
            ->andWhere("owner.id = :owner")
            ->setParameter("owner", $user->getId(), UlidType::NAME);

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
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    public function acceptShipmentBid(Ulid $id): ShipmentOrder
    {
        $user = $this->getUser();
        $bid = $this->getUserShipmentBid($id);
        $shipment = $bid->getShipment();


        if ($shipment->getShipmentOrder() != null) {
            throw new UserError("This shipment already has an order");
        }


        if ($bid->getStatus() != ShipmentBidStatus::PENDING) {
            throw new UserError("This bid cannot be accepted");
        }

        $code = $this->codeGenerator->generateCode(length: 6);
        $price = $bid->getPrice(); //?? $shipment->getBudget();
        $currency = $price?->getCurrency();
        $amount = $price?->getAmount();

        $order = new ShipmentOrder();
        $order
            ->setCode($code)
            ->setVehicle($bid->getVehicle())
            ->setShipment($shipment)
            ->setBid($bid)
            ->setDriver($bid->getDriver())
            ->setShipper($user)
            ->setCurrency($currency)
            ->setSubtotal($amount)
            ->setPickupAt($bid->getPickupAt() ?? $shipment->getPickupAt())
            ->setDeliveryAt($bid->getDeliveryAt() ?? $shipment->getDeliveryAt());

        $shipment
            ->setShipmentOrder($order)
            ->setStatus(ShipmentStatus::PROCESSING);
        $bid->setStatus(ShipmentBidStatus::ACCEPTED);

        $this->entityManager->persist($shipment);
        $this->entityManager->flush();

        return $order;
    }





    #[GQL\Mutation()]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    public function rejectShipmentBid(Ulid $id): bool
    {
        $user = $this->getUser();
        $bid = $this->getUserShipmentBid($id);
        $shipment = $bid->getShipment();


        if ($shipment->getShipmentOrder()?->getBid() == $bid) {
            throw new UserError("This shipment bid already has an order");
        }


        if ($bid->getStatus() != ShipmentBidStatus::PENDING) {
            throw new UserError("This bid cannot be rejected");
        }

        $bid->setStatus(ShipmentBidStatus::REJECTED);

        $this->entityManager->persist($shipment);
        $this->entityManager->flush();


        return true;
    }




    #[GQL\Mutation()]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    public function resolveShipmentBidChannel(Ulid $id): AbstractChatChannel
    {
        $bid = $this->getUserShipmentBid($id);
        $driver = $bid->getDriver();
        $driverUserAccount = $driver->getUserAccount();
        $shipment = $bid->getShipment();
        $user = $this->getUser();

        // $subject = $this->queryBus->query(new FindCha)

        $conversation = $this->dmResolver->resolveSendingConversation(
            $user,
            $driverUserAccount,
            true
        );
        $channel = $conversation->getChannel();

        $subject = new ChatSubject();
        $title = sprintf('Conversation on a shipment bid');
        $reference = sprintf('%s:%s', 'shipment.bid.conversation', $bid->getId());
        $subject
            ->setTitle($title)
            // ->setReference($reference)
            ;

        // $message 

        // $this->entityManager->persist($shipment);
        // $this->entityManager->flush();


        return $channel;
    }




    private function getUser(): User
    {
        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }
        return $user;
    }

    private function getUserShipmentBid(Ulid $id): ShipmentDriverBid
    {
        $bid = $this->shipmentDriverBidRepository->find($id);
        if (null == $bid || $bid->getShipment()?->getOwner() != $this->getUser()) {
            throw new UserError("Could not find bid with [id:{$id}] on your shipments");
        }
        return $bid;
    }


    private function getUserShipment(Ulid $id, User $user): Shipment
    {
        $shipment = $this->shipmentRepository->find($id);
        if (null == $shipment || $shipment->getOwner() != $user) {
            throw new UserError("Could not find your product with [id:{$id}]");
        }
        return $shipment;
    }

    private function getUserProduct(Ulid $id, User $user): UserProduct
    {
        $product = $this->userProductRepository->find($id);
        if (null == $product || $product->getOwner() != $user) {
            throw new UserError("Could not find your product with [id:{$id}]");
        }
        return $product;
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

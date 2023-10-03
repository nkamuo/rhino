<?php

namespace App\GraphQL\Shipment\Resolver;

use App\Entity\Account\User;
use App\Entity\Shipment\Document\ShipmentDocument;
use App\Entity\Shipment\Document\ShipmentDocumentAttachment;
use App\Entity\Shipment\Shipment;
use App\Entity\Shipment\ShipmentExecution;
use App\Entity\Shipment\ShipmentExecutionStatus;
use App\Entity\Shipment\ShipmentOrder;
use App\Entity\Shipment\ShipmentOrderOrderStatus;
use App\Entity\Shipment\ShipmentOrderStatus;
use App\Entity\Shipment\ShipmentStatus;
use App\Entity\Vehicle\Vehicle;
use App\GraphQL\Shipment\Input\Document\ShipmentOrderAttachmentInput;
use App\GraphQL\Shipment\Input\Document\ShipmentOrderDocumentInput;
use App\GraphQL\Shipment\Input\ShipmentOrderBidCreationInput;
use App\GraphQL\Shipment\Input\ShipmentOrderEstimatedNodeArrivalInput;
use App\GraphQL\Shipment\Input\ShipmentOrderNodeExecutionInput;
use App\GraphQL\Shipment\Type\ShipmentOrderConnection;
use App\GraphQL\Shipment\Type\ShipmentOrderDriverBidConnection;
use App\GraphQL\Shipment\Type\ShipmentOrderDriverBidEdge;
use App\GraphQL\Shipment\Type\ShipmentOrderEdge;
use App\Repository\Shipment\ShipmentOrderDriverBidRepository;
use App\Repository\Shipment\ShipmentOrderRepository;
use App\Repository\Vehicle\VehicleRepository;
use App\Service\File\UploaderInterface;
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
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        private UploaderInterface $uploader,
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
            ->setDriver($driver);

        $execution
            ->addOrder($order)
            ->setStatus(ShipmentExecutionStatus::PROCESSING);
        $order->setStatus(ShipmentOrderStatus::PROCESSING);

        $this->entityManager->persist($execution);
        $this->entityManager->flush();

        return $order;
    }



    #[GQL\Mutation()]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    #[GQL\Arg(name: 'input', type: 'ShipmentOrderEstimatedNodeArrivalInput!')]
    public function updateShipmentOrderEstimatedPickup(Ulid $id, ShipmentOrderEstimatedNodeArrivalInput $input): ShipmentOrder
    {
        $order = $this->getDriverShipmentOrder($id);

        if (($status = $order->getExecution()) == null) {
            throw new UserError(sprintf("This shipment order execution is not started yet"));
        }

        $order
            ->setExpectedPickupAt($input->datetime);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }



    #[GQL\Mutation()]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    #[GQL\Arg(name: 'input', type: 'ShipmentOrderEstimatedNodeArrivalInput!')]
    public function updateShipmentOrderEstimatedDelivery(Ulid $id, ShipmentOrderEstimatedNodeArrivalInput $input): ShipmentOrder
    {
        $order = $this->getDriverShipmentOrder($id);

        if (($status = $order->getExecution()) == null) {
            throw new UserError(sprintf("This shipment order execution is not started yet"));
        }

        $order
            ->setExpectedDeliveryAt($input->datetime);
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }



    #[GQL\Mutation()]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    #[GQL\Arg(name: 'input', type: 'ShipmentOrderNodeExecutionInput!')]
    public function executeShipmentOrderPickup(Ulid $id, ShipmentOrderNodeExecutionInput $input): ShipmentOrder
    {
        $order = $this->getDriverShipmentOrder($id);
        $shipment = $order->getShipment();

        if (($status = $order->getExecution()) == null) {
            throw new UserError(sprintf("This shipment order execution is not started yet"));
        }

        if (($status = $order->getStatus()) != ShipmentOrderStatus::PROCESSING) {
            throw new UserError(sprintf("This shipment order is not in \"%s\" state yet. Currently \"\%s\"", ShipmentOrderStatus::PROCESSING->name, $status->name));
        }

        $document = $this->handleDocument(
            shipment: $shipment,
            input: $input->document,
        );
        $order->setPickupConfirmation($document);
        $order->setStatus(ShipmentOrderStatus::INTRANSIT);
        $shipment->setStatus(ShipmentStatus::INTRANSIT);

        $this->entityManager->persist($shipment);
        $this->entityManager->flush();

        return $order;
    }



    #[GQL\Mutation()]
    #[GQL\Arg(name: 'id', type: 'Ulid!')]
    #[GQL\Arg(name: 'input', type: 'ShipmentOrderNodeExecutionInput!')]
    public function executeShipmentOrderDelivery(Ulid $id, ShipmentOrderNodeExecutionInput $input): ShipmentOrder
    {
        $order = $this->getDriverShipmentOrder($id);
        $shipment = $order->getShipment();

        if (($status = $order->getExecution()) == null) {
            throw new UserError(sprintf("This shipment order execution is not started yet"));
        }

        if (($status = $order->getStatus()) != ShipmentOrderStatus::INTRANSIT) {
            throw new UserError(sprintf("This shipment order is not in \"%s\" state yet. Currently \"\%s\"", ShipmentOrderStatus::INTRANSIT->name, $status->name));
        }

        $document = $this->handleDocument(
            shipment: $shipment,
            input: $input->document,
        );
        $order->setProofOfDelivery($document);
        $order->setStatus(ShipmentOrderStatus::DELIVERED);
        $shipment->setStatus(ShipmentStatus::DELIVERED);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }



    private function handleDocument(Shipment $shipment, ShipmentOrderDocumentInput $input): ShipmentDocument
    {

        $document = new ShipmentDocument();
        $document
            ->setType($input->type)
            ->setMeta($input->meta);
        foreach ($input->attachments as $aInput) {
            $attachment = $this->handleDocumentAttachment(shipment: $shipment, input: $aInput);
            $document->addAttachment($attachment);
        }
        return $document;
    }



    private function handleDocumentAttachment(Shipment $shipment, ShipmentOrderAttachmentInput $input): ShipmentDocumentAttachment
    {
        $uri = $this->handleFileUpload(shipment: $shipment, file: $input->src);
        $attachment = new ShipmentDocumentAttachment();
        $attachment
            ->setType($input->type)
            ->setSrc($uri)
            ->setCaption($input->caption)
            ->setMeta($input->meta);
        return $attachment;
    }


    private function handleFileUpload(Shipment $shipment,  UploadedFile $file)
    {
        $path = sprintf('shipment/%s/documents/attachments', $shipment->getCode());
        $uri = $this->uploader->upload($file, $path);
        return $uri;
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


    private function getShipmentOrderById(Ulid $id): ShipmentOrder
    {
        $shipment = $this->shipmentOrderRepository->find($id);
        if (null == $shipment) {
            throw new UserError("Cannot find shipment with [id: {$id}]");
        }
        return $shipment;
    }
}

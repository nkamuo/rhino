<?php

namespace App\GraphQL\Shipment\Resolver;

use App\Entity\Account\User;
use App\Entity\Addressing\UserAddress;
use App\Entity\Catalog\UserProduct;
use App\Entity\Shipment\Document\ShipmentDocument;
use App\Entity\Shipment\Shipment;
use App\Entity\Shipment\ShipmentBidStatus;
use App\Entity\Shipment\ShipmentDriverBid;
use App\Entity\Shipment\ShipmentItem;
use App\Entity\Shipment\ShipmentOrder;
use App\Entity\Shipment\ShipmentStatus;
use App\GraphQL\Shipment\Input\ShipmentCreationInput;
use App\GraphQL\Shipment\Input\ShipmentItemInput;
use App\GraphQL\Shipment\Input\ShipmentPublicationInput;
use App\GraphQL\Shipment\Type\ShipmentConnection;
use App\GraphQL\Shipment\Type\ShipmentDocumentConnection;
use App\GraphQL\Shipment\Type\ShipmentDocumentEdge;
use App\GraphQL\Shipment\Type\ShipmentDriverBidConnection;
use App\GraphQL\Shipment\Type\ShipmentDriverBidEdge;
use App\GraphQL\Shipment\Type\ShipmentEdge;
use App\Repository\Addressing\UserAddressRepository;
use App\Repository\Catalog\UserProductRepository;
use App\Repository\Shipment\Document\ShipmentDocumentRepository;
use App\Repository\Shipment\ShipmentDriverBidRepository;
use App\Repository\Shipment\ShipmentRepository;
use App\Service\Google\DirectionsServiceInterface;
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
    targetQueryTypes: ['ClientQuery'],
    targetMutationTypes: ['ClientMutation']
)]
class ClientShipmentDocumentResolver
{


    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private ShipmentRepository $shipmentRepository,
        private ShipmentDocumentRepository $shipmentDocumentRepository,
    ) {
    }






    #[Query(name: "get_shipment_document_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    public function getShipmentItem(Ulid $id): ShipmentDocument
    {

        $document = $this->shipmentDocumentRepository->find($id);
        if ($document === null) {
            throw new UserError(
                message: "Cannot find shipment document with [id:$id]"
            );
        }

        // if (!$this->security->isGranted('view', $shipment)) {
        //     throw new UserError(
        //         message: "Permision Denied: You may not view this resource"
        //     );
        // }
        return $document;
    }

    #[GQL\Query(name: "get_shipment_document_list")]
    // #[GQL\Access("isGranted('ROLE_USER')")]
    public function getShipmentConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): ShipmentDocumentConnection {


        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ShipmentDocumentConnection($edges, $pageInfo),
            fn (string $coursor, ShipmentDocument $document, int $index) => new ShipmentDocumentEdge($coursor, $document)
        );

        $qb = $this->shipmentDocumentRepository->createQueryBuilder('document');

        $qb
            ->innerJoin('document.shipmentOrder', 'shipmentOrder')
            ->innerJoin('shipmentOrder.shipment', 'shipment')
            ->innerJoin('shipment.owner', 'owner')
            ->andWhere("owner.id = :owner")
            ->setParameter("owner", $user->getId(), UlidType::NAME);


        QueryBuilderHelper::applyCriteria($qb, $filter, 'document');

        $total = fn () => (int) (clone $qb)->select('COUNT(document.id)')->getQuery()->getSingleScalarResult();
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($qb) {
            return $qb
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
        }, false, $cb);

        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }
}

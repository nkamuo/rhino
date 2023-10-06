<?php

namespace App\GraphQL\Shipment\Resolver;

use App\Entity\Account\User;
use App\Entity\Shipment\Assessment\Review;
use App\GraphQL\Shipment\Type\ShipmentAssessmentConnection;
use App\GraphQL\Shipment\Type\ShipmentAssessmentEdge;
use App\Repository\Shipment\Assessment\ReviewRepository;
use App\Repository\Shipment\ShipmentRepository;
use App\Util\Doctrine\QueryBuilderHelper;
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
    targetMutationTypes: ['DriverMutation']
)]
class DriverShipmentAssessmentResolver
{


    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private ShipmentRepository $shipmentRepository,
        private ReviewRepository $reviewRepository
    ) {
    }


    #[Query(name: "get_shipment_assessment_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    public function getShipmentReviewItem(Ulid $id): Review
    {

        $assessment = $this->reviewRepository->find($id);
        if ($assessment === null) {
            throw new UserError(
                message: "Cannot find shipment assessment assessment with [id:$id]"
            );
        }
        return $assessment;
    }

    #[GQL\Query(name: "get_shipment_assessment_item")]
    public function getShipmentReviewConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): ShipmentAssessmentConnection {


        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ShipmentAssessmentConnection($edges, $pageInfo),
            fn (string $coursor, Review $assessment, int $index) => new ShipmentAssessmentEdge($coursor, $assessment)
        );

        $qb = $this->reviewRepository->createQueryBuilder('assessment');

        $driver = $this->getDriver();

        $qb
            ->innerJoin('assessment.shipmentOrder', 'shipmentOrder')
            ->innerJoin('shipmentOrder.driver', 'driver')
            ->andWhere('driver.id = :driver')
            ->setParameter('driver', $driver->getId(), UlidType::NAME);

        QueryBuilderHelper::applyCriteria($qb, $filter, 'assessment');

        $total = fn () => (int) (clone $qb)->select('COUNT(assessment.id)')->getQuery()->getSingleScalarResult();
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
}

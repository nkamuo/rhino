<?php

namespace App\GraphQL\Shipment\Resolver;

use App\Entity\Account\User;
use App\Entity\Shipment\Assessment\AssessmentParameter;
use App\Entity\Shipment\Assessment\Review;
use App\GraphQL\Shipment\Type\ShipmentAssessmentConnection;
use App\GraphQL\Shipment\Type\ShipmentAssessmentEdge;
use App\GraphQL\Shipment\Type\ShipmentAssessmentParameterConnection;
use App\GraphQL\Shipment\Type\AssessmentParameterEdge;
use App\GraphQL\Shipment\Type\ShipmentAssessmentParameterEdge;
use App\Repository\Shipment\Assessment\AssessmentParameterRepository;
use App\Repository\Shipment\Assessment\ReviewRepository;
use App\Repository\Shipment\ShipmentRepository;
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
class ClientShipmentAssessmentResolver
{


    public function __construct(
        private Security $security,
        private ReviewRepository $reviewRepository,
        private EntityManagerInterface $entityManager,
        private ShipmentRepository $shipmentRepository,
        private AssessmentParameterRepository $assessmentParameterRepository,
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

    #[GQL\Query(name: "get_shipment_assessment_list")]
    public function getShipmentReviewConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): ShipmentAssessmentConnection {

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ShipmentAssessmentConnection($edges, $pageInfo),
            fn (string $coursor, Review $assessment, int $index) => new ShipmentAssessmentEdge($coursor, $assessment)
        );

        $qb = $this->reviewRepository->createQueryBuilder('assessment');

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



    


    #[Query(name: "get_shipment_assessment_parameter_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    public function getShipmentItem(Ulid $id): AssessmentParameter
    {

        $parameter = $this->assessmentParameterRepository->find($id);
        if ($parameter === null) {
            throw new UserError(
                message: "Cannot find shipment assessment parameter with [id:$id]"
            );
        }

        // if (!$this->security->isGranted('view', $shipment)) {
        //     throw new UserError(
        //         message: "Permision Denied: You may not view this resource"
        //     );
        // }
        return $parameter;
    }

    #[GQL\Query(name: "get_shipment_assessment_parameter_list")]
    // #[GQL\Access("isGranted('ROLE_USER')")]
    public function getShipmentConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): ShipmentAssessmentParameterConnection {


        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new ShipmentAssessmentParameterConnection($edges, $pageInfo),
            fn (string $coursor, AssessmentParameter $parameter, int $index) => new ShipmentAssessmentParameterEdge($coursor, $parameter)
        );

        $qb = $this->assessmentParameterRepository->createQueryBuilder('parameter');

        QueryBuilderHelper::applyCriteria($qb, $filter, 'parameter');

        $total = fn () => (int) (clone $qb)->select('COUNT(parameter.id)')->getQuery()->getSingleScalarResult();
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

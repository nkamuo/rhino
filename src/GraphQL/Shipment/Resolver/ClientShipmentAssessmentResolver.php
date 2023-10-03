<?php

namespace App\GraphQL\Shipment\Resolver;

use App\Entity\Account\User;
use App\Entity\Shipment\Assessment\AssessmentParameter;
use App\GraphQL\Shipment\Type\ShipmentAssessmentParameterConnection;
use App\GraphQL\Shipment\Type\AssessmentParameterEdge;
use App\GraphQL\Shipment\Type\ShipmentAssessmentParameterEdge;
use App\Repository\Shipment\Assessment\AssessmentParameterRepository;
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
        private EntityManagerInterface $entityManager,
        private ShipmentRepository $shipmentRepository,
        private AssessmentParameterRepository $assessmentParameterRepository
    ) {
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

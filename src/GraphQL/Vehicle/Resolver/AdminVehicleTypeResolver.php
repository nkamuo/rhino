<?php

namespace App\GraphQL\Vehicle\Resolver;


use App\Entity\Vehicle\VehicleType;
use App\GraphQL\Vehicle\Input\VehicleTypeCreationInput;
use App\GraphQL\Vehicle\Input\VehicleTypeUpdateInput;
use App\GraphQL\Vehicle\Type\VehicleTypeConnection;
use App\GraphQL\Vehicle\Type\VehicleTypeEdge;
use App\Repository\Vehicle\VehicleTypeRepository;
use App\Service\Identity\CodeGeneratorInterface;
use App\Util\Doctrine\QueryBuilderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider(
    targetQueryTypes: ['AdminQuery'],
    targetMutationTypes: ['AdminMutation'],
)]
class AdminVehicleTypeResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private VehicleTypeRepository $vehicleTypeRepository,
        private Security $security,
        private CodeGeneratorInterface $codeGenerator,
    ) {
    }


    #[Query(name: "get_vehicle_type_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid'
    )]
    public function getVehicleTypeItem(
        Ulid $id
    ): VehicleType {
        return $this->getVehicleTypeById($id);
    }

    #[GQL\Query(name: "get_vehicle_type_list")]
    public function getVehicleTypeConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): VehicleTypeConnection {


        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new VehicleTypeConnection($edges, $pageInfo),
            fn (string $coursor, VehicleType $vehicleType, int $index) => new VehicleTypeEdge($coursor, $vehicleType)
        );

        $qb = $this->vehicleTypeRepository->createQueryBuilder('vehicleType');
        QueryBuilderHelper::applyCriteria($qb, $filter, 'vehicleType');

        $total = fn () => (int) (clone $qb)->select('COUNT(vehicleType.id)')->getQuery()->getSingleScalarResult();
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
    public function createNewVehicleType(VehicleTypeCreationInput $input): VehicleType
    {
        $vehicleType = new VehicleType();

        $vehicleType
            ->setCode($input->code ?? $this->codeGenerator->generateCode(length: 8))
            ->setShortName($input->shortName)
            ->setName($input->name)
            ->setIconImage($input->iconImage)
            ->setPrimaryImage($input->primaryImage)
            ->setCoverImage($input->coverImage)
            ->setClientNote($input->clientNote)
            ->setDriverNote($input->driverNote)
            ->setDescription($input->description);

        $this->entityManager->persist($vehicleType);
        $this->entityManager->flush();

        return $vehicleType;
    }




    #[GQL\Mutation()]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    #[GQL\Arg(
        name: 'input',
        type: 'VehicleTypeUpdateInput!'
    )]
    public function updateVehicleType(Ulid $id, VehicleTypeUpdateInput $input): VehicleType
    {
        $vehicleType = $this->getVehicleTypeById($id);

        $vehicleType
            ->setCode($input->code ?? $this->codeGenerator->generateCode(length: 8))
            ->setShortName($input->shortName)
            ->setName($input->name)
            ->setIconImage($input->iconImage)
            ->setPrimaryImage($input->primaryImage)
            ->setCoverImage($input->coverImage)
            ->setClientNote($input->clientNote)
            ->setDriverNote($input->driverNote)
            ->setDescription($input->description);

        $this->entityManager->persist($vehicleType);
        $this->entityManager->flush();

        return $vehicleType;
    }

    
    #[GQL\Mutation()]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    public function deleteVehicleType(Ulid $id): VehicleType{
        $vehicleType = $this->getVehicleTypeById($id);
        $this->entityManager->remove($vehicleType);
        $this->entityManager->flush();
        return $vehicleType;
    }



    private function getVehicleTypeById(Ulid $id): VehicleType
    {
        $vType = $this->vehicleTypeRepository->find($id);
        if (null == $vType) {
            throw new UserError("Could not find Vehicle type with [id:{$id}]");
        }
        return $vType;
    }
}

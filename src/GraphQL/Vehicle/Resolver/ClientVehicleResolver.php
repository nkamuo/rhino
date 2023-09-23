<?php

namespace App\GraphQL\Vehicle\Resolver;

use App\Entity\Account\User;
use App\Entity\Vehicle\Vehicle;
use App\Entity\Vehicle\VehicleType;
use App\Entity\Vehicle\DriverVehicle;
use App\GraphQL\Vehicle\Input\VehicleCreationInput;
use App\GraphQL\Vehicle\Input\VehicleUpdateInput;
use App\GraphQL\Vehicle\Type\VehicleConnection;
use App\GraphQL\Vehicle\Type\VehicleEdge;
use App\Repository\Vehicle\VehicleTypeRepository;
use App\Repository\Vehicle\VehicleRepository;
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
    targetQueryTypes: ['ClientQuery'],
    targetMutationTypes: ['ClientMutation']
)]
class ClientVehicleResolver
{

    public function __construct(
        private VehicleTypeRepository $vehicleCategoryRepository,
        private VehicleRepository $vehicleRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }



    #[Query(name: "get_vehicle_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid'
    )]
    public function getVehicleItem(
        #[GQL\Arg(type: 'Ulid')] Ulid $id
    ): Vehicle {

        $vehicle = $this->getVehicleById($id);

        // if (!$this->security->isGranted('view', $vehicle)) {
        //     throw new UserError(
        //         message: "Permision Denied: You may not view this resource"
        //     );
        // }



        return $vehicle;
    }

    #[GQL\Query(name: "get_vehicle_list")]
    #[GQL\Access("isGranted('ROLE_USER')")]
    public function getVehicleConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): VehicleConnection {

        $user = $this->getUser();
        $driver = $user->getDriver();

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new VehicleConnection($edges, $pageInfo),
            fn (string $coursor, Vehicle $vehicle, int $index) => new VehicleEdge($coursor, $vehicle)
        );

        $qb = $this->vehicleRepository->createQueryBuilder('vehicle');


        $qb
            ->innerJoin('vehicle.driver', 'driver')
            ->andWhere("driver.id = :driver")
            ->setParameter("driver", $driver->getId(), UlidType::NAME);

        QueryBuilderHelper::applyCriteria($qb, $filter, 'vehicle');

        $total = fn () => (int) (clone $qb)->select('COUNT(vehicle.id)')->getQuery()->getSingleScalarResult();
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
    public function createNewVehicle(VehicleCreationInput $input): Vehicle
    {

        $user = $this->getUser();
        $driver = $user->getDriver();
       
        $vehicleType = $this->getVehicleTypeById($input->vehicleTypeId);

        $vehicle = new Vehicle();
        $input->build($vehicle);
        $vehicle->setType($vehicleType);
        $vehicle->setDriver($driver);

        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();

        return $vehicle;
    }



    #[GQL\Mutation()]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    #[GQL\Arg(
        name: 'input',
        type: 'VehicleUpdateInput!'
    )]
    public function updateVehicle(Ulid $id, VehicleUpdateInput $input): Vehicle
    {
        $vehicle = $this->getVehicleById($id);
        $vehicleType = $this->getVehicleTypeById($input->vehicleTypeId);

        $input->build($vehicle);
        $vehicle->setType($vehicleType);

        $this->entityManager->persist($vehicle);
        $this->entityManager->flush();

        return $vehicle;
    }




    private function getUser(): User
    {
        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }
        return $user;
    }


    private function getVehicleById(Ulid $id): Vehicle
    {
        $user = $this->getUser();
        $driver = $user?->getDriver();
        $vehicle = $this->vehicleRepository->find($id);
        if ($vehicle === null) {
            throw new UserError(
                message: "Cannot find vehicle with [id:$id]"
            );
        }
        if ($vehicle->getDriver() !== $driver) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }
        return $vehicle;
    }


    private function getVehicleTypeById(Ulid $id): VehicleType
    {
        $vType = $this->vehicleCategoryRepository->find($id);
        if (null == $vType) {
            throw new UserError("Could not find Vehicle type with [id:{$id}]");
        }
        return $vType;
    }
}

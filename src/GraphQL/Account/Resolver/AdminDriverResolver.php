<?php

namespace App\GraphQL\Account\Resolver;


use App\Entity\Account\Driver;
use App\Entity\Account\User;
use App\GraphQL\Account\Input\AdminDriverCreationInput;
use App\GraphQL\Account\Input\DriverCreationInput;
use App\GraphQL\Account\Input\DriverUpdateInput;
use App\GraphQL\Account\Type\DriverConnection;
use App\GraphQL\Account\Type\DriverEdge;
use App\Repository\Account\DriverRepository;
use App\Repository\Account\UserRepository;
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
class AdminDriverResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriverRepository $driverRepository,
        private UserRepository $userRepository,
        private Security $security,
    ) {
    }



    #[Query(name: "get_current_driver",)]
    public function getCurrentDriver(): Driver
    {

        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError(
                message: "Cannot find current driver"
            );
        }

        $driver = $user->getDriver();
        if (null == $driver)
            throw new UserError("Could not find the driver account associated with the current user profile");

        return $driver;
    }

    #[Query(name: "get_driver_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid'
    )]
    public function getDriverItem(
        #[GQL\Arg(type: 'Ulid')] Ulid $id
    ): Driver {

        $driver = $this->driverRepository->find($id);
        if ($driver === null) {
            throw new UserError(
                message: "Cannot find driver with [id:$id]"
            );
        }

        // if (!$this->security->isGranted('view', $driver)) {
        //     throw new UserError(
        //         message: "Permision Denied: You may not view this resource"
        //     );
        // }

        return $driver;
    }

    #[GQL\Query(name: "get_driver_list")]
    public function getDriverConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): DriverConnection {


        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new DriverConnection($edges, $pageInfo),
            fn (string $coursor, Driver $driver, int $index) => new DriverEdge($coursor, $driver)
        );

        $qb = $this->driverRepository->createQueryBuilder('driver');
        QueryBuilderHelper::applyCriteria($qb, $filter, 'driver');

        $total = fn () => (int) (clone $qb)->select('COUNT(driver.id)')->getQuery()->getSingleScalarResult();
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
    public function createNewDriver(AdminDriverCreationInput $input): Driver
    {
        $user = $this->getUserById($input->userId);

        if ($user->getDriver() != null) {
            throw new UserError("The referenced User account is already associated with a driver profile");
        }

        $driver = new Driver();
        $input->build($driver);

        $driver->setUserAccount($user);

        $this->entityManager->persist($driver);
        $this->entityManager->flush();

        return $driver;
    }



    #[GQL\Mutation()]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    #[GQL\Arg(
        name: 'input',
        type: 'DriverUpdateInput!'
    )]
    public function updateDriver(Ulid $id, DriverUpdateInput $input): Driver
    {

        $driver = $this->getDriverById($id);
        $input->build($driver);

        $this->entityManager->persist($driver);
        $this->entityManager->flush();

        return $driver;
    }


    private function getDriverById(Ulid $id): Driver
    {
        $driver = $this->driverRepository->find($id);
        if (null === $driver) {
            throw new UserError("Cannot find driver with [id:{$id}]");
        }
        return $driver;
    }
    
    private function getUserById(Ulid $id): User
    {
        $user = $this->userRepository->find($id);
        if (null === $user) {
            throw new UserError("Cannot find user with [id:{$id}]");
        }
        return $user;
    }
}

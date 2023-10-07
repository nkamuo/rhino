<?php

namespace App\GraphQL\Account\Resolver;


use App\Entity\Account\Driver;
use App\Entity\Account\User;
use App\Entity\Document\DriverLicense;
use App\Repository\Account\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Arg;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider(
    targetQueryTypes: ['ClientQuery'],
    targetMutationTypes: ['ClientMutation'],
)]
class ClientDriverResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriverRepository $driverRepository,
        private Security $security,
    ) {
    }


    #[Query(name: "get_driver_item",)]
    #[Arg(name: 'id', type: 'Ulid')]
    public function getDriverItem(?Ulid $id): ?Driver
    {
        return $this->getDriver($id);
    }



    private function getDriver(?Ulid $id): Driver
    {
        if (null == $id) {
            $driver = $this->fetchCurrentDriver();
        } else {
            $driver =  $this->driverRepository->find($id);
            if (null == $driver) {
                throw new UserError("Could not find driver with [id:{$id}]");
            }
        }
        return $driver;
    }


    private function fetchCurrentDriver(): Driver
    {
        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError(
                message: "Cannot find current user"
            );
        }
        $driver = $user->getDriver();

        if (null == $driver) {
            throw new UserError("Could not resolve the current driver account");
        }
        return $driver;
    }
}

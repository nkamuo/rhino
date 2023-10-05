<?php

namespace App\GraphQL\Account\Resolver;


use App\Entity\Account\Driver;
use App\Entity\Account\User;
use App\GraphQL\Account\Input\DriverUpdateInput;
use App\Repository\Account\DriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Mutation;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider(
    targetQueryTypes: ['DriverQuery'],
    targetMutationTypes: ['DriverMutation'],
)]
class DriverQueryResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private DriverRepository $driverRepository,
        private Security $security,
    ) {
    }


    #[Query(name: "get_current_driver",)]
    public function getCurrentDriver(): Driver
    {
        $driver = $this->getDriver();
        return $driver;
    }

    #[Mutation()]
    public function updateProfile(
        DriverUpdateInput $input,
    ): Driver {

        $driver = $this->getDriver();

        $input->build($driver);

        $this->entityManager->persist($driver);
        $this->entityManager->flush();

        return $driver;
    }




    private function getDriver(): Driver
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

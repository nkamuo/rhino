<?php

namespace App\GraphQL\Account\Resolver;


use App\Entity\Account\Driver;
use App\Entity\Account\DriverAddress;
use App\Entity\Account\User;
use App\GraphQL\Account\Input\DriverAddressInput;
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
class DriverAddressResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }


    #[Query(name: "get_address",)]
    public function getCurrentDriverAddress(): ?DriverAddress
    {
        return $this->getDriver()->getAddress();
    }


    #[Mutation()]
    public function updateAddress(
        DriverAddressInput $input,
        ?String $clientMutationId = null,
    ): ?DriverAddress {

        $driver = $this->getDriver();
        $address = $driver->getAddress() ?? new DriverAddress();
        $input->build($address);
        
        $driver->setAddress($address);
        
        $this->entityManager->persist($driver);
        $this->entityManager->flush();

        return $address;
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

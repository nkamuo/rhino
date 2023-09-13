<?php
namespace App\GraphQL\Addressing\Resolver;

use App\Entity\Account\User;
use App\Entity\Addressing\Address;
use App\Entity\Addressing\UserAddress;
use App\GraphQL\Addressing\Input\AdminAddressCreationInput;
use App\Repository\Account\UserRepository;
use App\Repository\Addressing\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;

#[GQL\Provider(targetMutationTypes:['AdminMutation'])]
class AdminAddressResolver
{

    public function __construct(
        private AddressRepository $addressRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
        private UserRepository $userRepository,
    ){

    }

    #[GQL\Mutation()]
    public function createNewAddress(AdminAddressCreationInput $input): Address{

        $user = $this->getUserById($input->userId);

        $address = new UserAddress();
        $input->build($address);

        $address->setOwner($user);
        $this->entityManager->persist($address);
        $this->entityManager->flush();

        return $address;
    }

    

    public function getUserById(Ulid $id): User
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new UserError("Could not find user with [id: {$id}]");
        }
        return $user;
    }
}

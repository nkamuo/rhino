<?php
namespace App\GraphQL\Addressing\Mutation;

use App\Entity\Account\User;
use App\Entity\Addressing\Address;
use App\Entity\Addressing\UserAddress;
use App\GraphQL\Addressing\Input\AddressInput;
use App\Repository\Addressing\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bundle\SecurityBundle\Security;

#[GQL\Provider()]
class AddressMutationResolver
{

    public function __construct(
        private AddressRepository $addressRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
    ){

    }

    #[GQL\Mutation()]
    public function createNewAddress(AddressInput $input): Address{

        $user = $this->security->getUser();
        if(!($user instanceof User)){
            throw new UserError("Permission Denied: You may not perform the said operation");
        }
        $address = new UserAddress();
        $input->build($address);

        $address->setOwner($user);
        $this->entityManager->persist($address);
        $this->entityManager->flush();

        return $address;
    }
}

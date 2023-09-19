<?php

namespace App\GraphQL\Addressing\Resolver;

use App\Entity\Account\User;
use App\Entity\Addressing\Address;
use App\Entity\Addressing\UserAddress;
use App\GraphQL\Addressing\Input\AdminAddressCreationInput;
use App\GraphQL\Addressing\Type\AddressConnection;
use App\GraphQL\Addressing\Type\AddressEdge;
use App\Repository\Account\UserRepository;
use App\Repository\Addressing\AddressRepository;
use App\Util\Doctrine\QueryBuilderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
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
class AdminAddressResolver
{

    public function __construct(
        private AddressRepository $addressRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
        private UserRepository $userRepository,
    ) {
    }




    #[GQL\Query(name: "get_address_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid'
    )]
    public function getAddressItem(
        #[GQL\Arg(type: 'Ulid')] Ulid $id
    ): Address {

        $address = $this->addressRepository->find($id);
        if ($address === null) {
            throw new UserError(
                message: "Cannot find address with [id:$id]"
            );
        }
        return $address;
    }

    #[GQL\Query(name: "get_address_list")]
    public function getAddressConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): AddressConnection {

        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }

        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new AddressConnection($edges, $pageInfo),
            fn (string $coursor, Address $address, int $index) => new AddressEdge($coursor, $address)
        );

        $qb = $this->addressRepository->createQueryBuilder('address');

        QueryBuilderHelper::applyCriteria($qb, $filter, 'address');

        $total = fn () => (int) (clone $qb)->select('COUNT(address.id)')->getQuery()->getSingleScalarResult();
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
    public function createNewAddress(AdminAddressCreationInput $input): Address
    {

        $user = $this->getUserById($input->ownerId);

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

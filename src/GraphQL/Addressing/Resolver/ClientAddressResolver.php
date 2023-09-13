<?php

namespace App\GraphQL\Addressing\Resolver;

use App\Entity\Account\User;
use App\Entity\Addressing\Address;
use App\Entity\Addressing\UserAddress;
use App\GraphQL\Addressing\Input\AddressCreationInput;
use App\GraphQL\Addressing\Input\AddressUpdateInput;
use App\GraphQL\Addressing\Type\AddressConnection;
use App\GraphQL\Addressing\Type\AddressEdge;
use App\Repository\Addressing\AddressRepository;
use App\Repository\Addressing\UserAddressRepository;
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
    targetMutationTypes: ['ClientMutation'],
)]
class ClientAddressResolver
{

    public function __construct(
        private UserAddressRepository $addressRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }



    #[Query(name: "get_address_item",)]
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

        // if (!$this->security->isGranted('view', $address)) {
        //     throw new UserError(
        //         message: "Permision Denied: You may not view this resource"
        //     );
        // }



        return $address;
    }

    #[GQL\Query(name: "get_address_list")]
    #[GQL\Access("isGranted('ROLE_USER')")]
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


        $qb
            ->innerJoin('address.owner', 'owner')
            ->andWhere("owner.id = :owner")
            ->setParameter("owner", $user->getId(), UlidType::NAME);

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
    public function createNewAddress(AddressCreationInput $input): Address
    {

        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }
        $address = new UserAddress();
        $input->build($address);

        $address->setOwner($user);
        $this->entityManager->persist($address);
        $this->entityManager->flush();

        return $address;
    }





    #[GQL\Mutation()]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid!'
    )]
    #[GQL\Arg(
        name: 'input',
        type: 'AddressUpdateInput!'
    )]
    public function updateAddress(Ulid $id,  AddressUpdateInput $input): Address
    {
        $address = $this->getAddressById($id);
        $user = $this->getUser();

        $input->build($address);

        $this->entityManager->persist($address);
        $this->entityManager->flush();

        return $address;
    }



    private function getUser(): User
    {
        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }
        return $user;
    }


    private function getAddressById(Ulid $id): Address
    {
        $user = $this->getUser();
        $address = $this->addressRepository->find($id);
        if ($address === null) {
            throw new UserError(
                message: "Cannot find address with [id:$id]"
            );
        }
        if ($address->getOwner() !== $user) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }
        return $address;
    }
}

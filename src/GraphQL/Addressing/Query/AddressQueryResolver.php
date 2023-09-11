<?php

namespace App\GraphQL\Addressing\Query;


use App\Entity\Addressing\Address;
use App\GraphQL\Addressing\Type\AddressConnection;
use App\GraphQL\Addressing\Type\AddressEdge;
use App\Repository\Addressing\AddressRepository;
use App\Util\Doctrine\QueryBuilderHelper;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Relay\Connection\ConnectionBuilder;
use Overblog\GraphQLBundle\Relay\Connection\PageInfoInterface;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider()]
class AddressQueryResolver
{

    public function __construct(
        private AddressRepository $addressRepository,
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

        if (!$this->security->isGranted('view', $address)) {
            throw new UserError(
                message: "Permision Denied: You may not view this resource"
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


        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new AddressConnection($edges, $pageInfo),
            fn (string $coursor, Address $address, int $index) => new AddressEdge($coursor, $address)
        );

        $qb = $this->addressRepository->createQueryBuilder('address');
        QueryBuilderHelper::applyCriteria($qb, $filter, 'address');

        $total = fn () => (int) (clone $qb)->select('COUNT(address.id)')->getQuery()->getSingleScalarResult();
        $paginator = new Paginator(function (?int $offset, ?int $limit) use ($qb) {
            return $qb->getQuery()->getResult();
        }, false, $cb);

        return $paginator->auto(new Argument(['first' => $first, 'after' => $after]), $total);
    }
}

<?php

namespace App\GraphQL\Account\Resolver;


use App\Entity\Account\User;
use App\GraphQL\Account\Input\UserCreationInput;
use App\GraphQL\Account\Type\UserConnection;
use App\GraphQL\Account\Type\UserEdge;
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
class AdminUserQueryResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private Security $security,
    ) {
    }



    #[Query(name: "get_current_user",)]
    public function getCurrentUser(): User
    {

        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError(
                message: "Cannot find current user"
            );
        }
        // if (!$this->security->isGranted('view', $user)) {
        //     throw new UserError(
        //         message: "Permision Denied: You may not view this resource"
        //     );
        // }
        return $user;
    }

    #[Query(name: "get_user_item",)]
    #[GQL\Arg(
        name: 'id',
        type: 'Ulid'
    )]
    public function getUserItem(
        #[GQL\Arg(type: 'Ulid')] Ulid $id
    ): User {

        $user = $this->userRepository->find($id);
        if ($user === null) {
            throw new UserError(
                message: "Cannot find user with [id:$id]"
            );
        }

        if (!$this->security->isGranted('view', $user)) {
            throw new UserError(
                message: "Permision Denied: You may not view this resource"
            );
        }

        return $user;
    }

    #[GQL\Query(name: "get_user_list")]
    public function getUserConnection(
        ?int $first,
        ?String $after,
        ?String $filter,
        ?String $sort,
    ): UserConnection {


        $cb = new ConnectionBuilder(
            null,
            fn ($edges, PageInfoInterface $pageInfo) => new UserConnection($edges, $pageInfo),
            fn (string $coursor, User $user, int $index) => new UserEdge($coursor, $user)
        );

        $qb = $this->userRepository->createQueryBuilder('user');
        QueryBuilderHelper::applyCriteria($qb, $filter, 'user');

        $total = fn () => (int) (clone $qb)->select('COUNT(user.id)')->getQuery()->getSingleScalarResult();
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
    public function createNewUser(UserCreationInput $input): User
    {

        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError("Permission Denied: You may not perform this operation");
        }

        $user = new User();
        $input->build($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}

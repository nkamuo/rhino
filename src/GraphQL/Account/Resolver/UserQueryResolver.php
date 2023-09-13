<?php

namespace App\GraphQL\Account\Resolver;


use App\Entity\Account\User;
use App\Repository\Account\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider()]
class UserQueryResolver
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
}

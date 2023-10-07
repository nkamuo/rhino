<?php

namespace App\GraphQL\Account\Resolver;


use App\Entity\Account\User;
use App\GraphQL\Account\Input\UserUpdateInput;
use App\Repository\Account\UserRepository;
use App\Service\File\UploaderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Annotation\Arg;
use Overblog\GraphQLBundle\Annotation\Mutation;
use Overblog\GraphQLBundle\Annotation\Query;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Ulid;


#[GQL\Provider(
    targetQueryTypes: ['ClientQuery'],
    targetMutationTypes: ['ClientMutation'],
)]
class UserResolver
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UploaderInterface $uploader,
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


    #[Mutation()]
    #[Arg(name: 'id', type: 'Ulid!')]
    #[Arg(name: 'input', type: 'UserUpdateInput!')]
    public function updateUser(Ulid $id, UserUpdateInput $input): User
    {
        $user = $this->getUserById($id);
        $input->build($user);

        if ($input->avatar) {
            if($url = $user->getAvatar()){
                // $this->uploader->remove($url);
            }
            $uploadPath = sprintf('users/%s/', $user->getId());
            $name = sprintf('avatar.%s', $input->avatar->getClientOriginalExtension());
            $url = $this->uploader->upload($input->avatar, $uploadPath, $name);
            $user->setAvatar($url);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }



    private function getUserById(Ulid $id): User
    {
        $user = $this->userRepository->find($id);
        if (null == $user) {
            throw new UserError("Could not find user with [id:{$id}]");
        }
        return $user;
    }

    private function fetchCurrentUser(): User
    {
        $user = $this->security->getUser();
        if (!($user instanceof User)) {
            throw new UserError(
                message: "Cannot find current user"
            );
        }
        return $user;
    }
}

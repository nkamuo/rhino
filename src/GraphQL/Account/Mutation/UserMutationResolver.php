<?php
namespace App\GraphQL\Account\Mutation;

use App\Entity\Account\User;
use App\GraphQL\Account\Input\UserCreationInput;
use App\Repository\Account\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Bundle\SecurityBundle\Security;

#[GQL\Provider()]
class UserMutationResolver
{

    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private Security $security,
    ){

    }

    #[GQL\Mutation()]
    public function createNewUser(UserCreationInput $input): User{
        
        $user = $this->security->getUser();
        if(!($user instanceof User)){
            throw new UserError("Permission Denied: You may not perform this operation");
        }

        $user = new User();
        $input->build($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}

<?php

namespace App\MessageHandler\Chat;

use App\CQRS\QueryBusInterface;
use App\Entity\Chat\ChatChannel;
use App\Message\Chat\Channel\CreateChannel;
use App\Message\Chat\Channel\DeleteChannel;
use App\Repository\Chat\ChatChannelRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final class ChatChannelMutationHandler
{


    public function __construct(
        private ChatChannelRepository $repository,
        private ChatChannelRepository $chatChannelRepository,
        private QueryBusInterface $queryBus
    ) {

    }

    #[AsMessageHandler]
    public function createChatChannel(CreateChannel $command)
    {
        $channel = new ChatChannel($command->getUid());

        $channel
            // ->setReference($command->get())
        ;

        $this->chatChannelRepository->save($channel, true);
    }



    // #[AsMessageHandler]
    // public function updateChatChannel(UpdateChatChannel $command)
    // {

    //     $message = $this->repository->find($command->getUid());

    //     if (null === $message) {

    //         throw new \LogicException('Cannot find the ChatChannel with the specified id');

    //         // return;
    //     }

    //     $message
    //         ->setCode($command->getCode())
    //         ->setName($command->getName())
    //         ->setAddress($command->getAddress())
    //         ->setEmailAddress($command->getEmailAddress())
    //         ->setPhoneNumber($command->getPhoneNumber())
    //         ->setNote($command->getNote())
    //         ->setDescription($command->getDescription())
    //     ;

    //     if($userId = $command->getUserId()){

    //         $user = $this->queryBus->query(new FindUserById($userId));
    //         if (!($user instanceof User))
    //             throw new \InvalidArgumentException("Could not find user with [id:{$userId}]");

    //         $message
    //             ->setUser($user)
    //             ->setUserId($user->getId());
    //     }

    //     $this->repository->save($message, true);
    // }


    #[AsMessageHandler]
    public function deleteChatChannel(DeleteChannel $command)
    {
        $message = $this->repository->find($command->getUid());
        if (null === $message) {
            return;
        }
        $this->repository->remove($message, true);

    }

}
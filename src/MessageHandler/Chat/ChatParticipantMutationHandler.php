<?php

namespace App\MessageHandler\Chat;

use App\CQRS\QueryBusInterface;
use App\Entity\Chat\ChatParticipant;
use App\Message\Chat\Participant\CreateParticipant;
use App\Message\Chat\Participant\DeleteParticipant;
use App\Repository\Chat\ChatParticipantRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final class ChatParticipantMutationHandler
{


    public function __construct(
        private ChatParticipantRepository $repository,
        private ChatParticipantRepository $chatParticipantRepository,
        private QueryBusInterface $queryBus
    ) {

    }

    #[AsMessageHandler]
    public function createChatParticipant(CreateParticipant $command)
    {
        // $channel = new ChatParticipant($command->getUid());

        // $channel
        //     // ->setReference($command->get())
        // ;

        // $this->chatParticipantRepository->save($channel, true);
    }




    #[AsMessageHandler]
    public function deleteChatParticipant(DeleteParticipant $command)
    {
        $message = $this->repository->find($command->getUid());
        if (null === $message) {
            return;
        }
        $this->repository->remove($message, true);

    }

}
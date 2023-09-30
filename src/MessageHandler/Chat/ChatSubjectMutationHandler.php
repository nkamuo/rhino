<?php

namespace App\MessageHandler\Chat;

use App\CQRS\QueryBusInterface;
use App\Entity\Chat\ChatSubject;
use App\Message\Chat\Subject\CreateSubject;
use App\Message\Chat\Subject\DeleteSubject;
use App\Repository\Chat\ChatSubjectRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

final class ChatSubjectMutationHandler
{


    public function __construct(
        private ChatSubjectRepository $repository,
        private ChatSubjectRepository $chatSubjectRepository,
        private QueryBusInterface $queryBus
    ) {

    }

    #[AsMessageHandler]
    public function createChatSubject(CreateSubject $command)
    {
        $channel = new ChatSubject($command->getUid());

        $channel
            // ->setReference($command->get())
        ;

        $this->chatSubjectRepository->save($channel, true);
    }




    #[AsMessageHandler]
    public function deleteChatSubject(DeleteSubject $command)
    {
        $message = $this->repository->find($command->getUid());
        if (null === $message) {
            return;
        }
        $this->repository->remove($message, true);

    }

}
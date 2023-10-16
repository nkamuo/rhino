<?php

namespace App\MessageHandler\Chat;

use App\CQRS\QueryBusInterface;
use App\Entity\Chat\AbstractChatChannel;
use App\Entity\Chat\AbstractChatParticipant;
use App\Entity\Chat\ChatChannel;
use App\Entity\Chat\ChatMessage;
use App\Entity\Chat\ChatMessageAttachment;
use App\Entity\Chat\ChatParticipant;
use App\Entity\Chat\ChatSubject;
use App\Message\Chat\Channel\FindChannelById;
use App\Message\Chat\Message\CreateChatMessage;
use App\Message\Chat\Message\DeleteChatMessage;
use App\Message\Chat\Participant\FindParticipantById;
use App\Message\Chat\Subject\FindSubjectById;
use App\Repository\Chat\AbstractChatChannelRepository;
use App\Repository\Chat\ChatChannelRepository;
use App\Repository\Chat\ChatMessageRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

final class ChatMessageMutationHandler
{


    public function __construct(
        private ChatMessageRepository $repository,
        private AbstractChatChannelRepository $chatChannelRepository,
        private QueryBusInterface $queryBus
    ) {
    }

    #[AsMessageHandler]
    public function createChatMessage(CreateChatMessage $command)
    {
        $message = new ChatMessage($command->getUid());

        $message
            ->setBody($command->getBody());


        $channel = $participant = $subject = null;

        if ($channelId = $command->getChannelId()) {
            $channel = $this->queryBus->query(new FindChannelById($channelId));
            if (!($channel instanceof AbstractChatChannel))
                throw new \InvalidArgumentException("Could not find channel with [id:{$channelId}]");
            $channel->addMessage($message);
        }

        if ($participantId = $command->getParticipantId()) {

            $participant = $this->queryBus->query(new FindParticipantById($participantId));
            if (!($participant instanceof AbstractChatParticipant))
                throw new \InvalidArgumentException("Could not find participant with [id:{$participantId}]");
            $message->setParticipant($participant);
        }

        if ($subjectId = $command->getSubjectId()) {
            $subject = $this->queryBus->query(new FindSubjectById($subjectId));
            if (!($subject instanceof ChatSubject))
                throw new \InvalidArgumentException("Could not find subject with [id:{$subjectId}]");
            $message->setSubject($subject);
            $subject->addMessage($message);
        }

        foreach ($command->getAttachments() as $file) {
            $attachment = new ChatMessageAttachment();
            $attachment->setUri($file->getUri());
            $message->addAttachment($attachment);
        }


        if (null === $channel)
            throw new UnrecoverableMessageHandlingException("Could not find Channel with [id:{$channelId}]");


        $this->chatChannelRepository->save($channel, true);
    }



    // #[AsMessageHandler]
    // public function updateChatMessage(UpdateChatMessage $command)
    // {

    //     $message = $this->repository->find($command->getUid());

    //     if (null === $message) {

    //         throw new \LogicException('Cannot find the ChatMessage with the specified id');

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
    public function deleteChatMessage(DeleteChatMessage $command)
    {
        $message = $this->repository->find($command->getUid());
        if (null === $message) {
            return;
        }
        $this->repository->remove($message, true);
    }
}

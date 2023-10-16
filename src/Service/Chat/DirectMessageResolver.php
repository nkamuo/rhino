<?php

namespace App\Service\Chat;

use App\Entity\Chat\DM\Conversation;
use App\Entity\Chat\DM\DMChatChannel;
use App\Entity\Account\User;
use App\Repository\Chat\DM\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;

class DirectMessageResolver implements DirectMessageResolverInterface
{
    public function __construct(
        private ConversationRepository $conversationRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function resolveDMConvsersation(User $userA, User $userB, bool $save = false): DMChatChannel
    {
        /** @var  DMChatChannel*/
        list($channel) = $this->doInitializeDMConvsersation($userA, $userB);
        if ($save) {
            $this->entityManager->persist($channel);
            $this->entityManager->flush();
        }
        return $channel;
    }


    public function resolveSendingConversation(
        User $userA,
        User $userB,
        bool $save = false
    ): Conversation {
        /** @var  DMChatChannel*/
        /** @var  Conversation*/
        list($channel, $sendConv, $receiveConv) = $this->doInitializeDMConvsersation($userA, $userB);
        if ($save) {
            $this->entityManager->persist($channel);
            $this->entityManager->flush();
        }
        return $sendConv;
    }


    /**
     * @return array<DMChatChannel,Conversation,Conversation>
     */
    private function doInitializeDMConvsersation(User $userA, User $userB): array
    {
        $conversationA = $this->conversationRepository->findOneBy([
            'sender' => $userA,
            'receiver' => $userB,
        ]);

        if (null == $conversationA) {
            $conversationA = new Conversation();
            $conversationA
                ->setSender($userA)
                ->setReceiver($userB);
        }

        $conversationB = $this->conversationRepository->findOneBy([
            'sender' => $userB,
            'receiver' => $userA,
        ]);

        if (null == $conversationB) {
            $conversationB = new Conversation();
            $conversationB
                ->setSender($userB)
                ->setReceiver($userA);
        }

        $channel = $conversationA->getChannel() ?? $conversationB->getChannel();

        if ($channel == null) {
            $channel = new DMChatChannel();
        }
        $channel
            ->addParticipant($conversationA)
            ->addParticipant($conversationB);

        return [$channel, $conversationA, $conversationB];
    }
}

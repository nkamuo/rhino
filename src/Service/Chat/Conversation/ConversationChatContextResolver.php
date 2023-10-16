<?php

namespace App\Service\Chat\Conversation;

use App\Composition\Security\SecurityCheckerInterface;
use App\CQRS\QueryBusInterface;
use App\Entity\Account\User;
use App\Entity\Chat\ChatChannel;
use App\Entity\Chat\ChatUserParticipant;
use App\Entity\Chat\DM\Conversation;
use App\Entity\Chat\DM\DMChatChannel;
use App\Repository\Account\UserRepository;
use App\Repository\Chat\AbstractChatChannelRepository;
use App\Repository\Chat\DM\ConversationRepository;
use App\Repository\Transport\Shipment\Order\ShipmentOrderRepository;
use App\Repository\Transport\Shipment\ShipmentRepository;
use App\Service\Chat\ChatContextResolverInterface;
use App\Service\Chat\Exception\ChatChannelParticipanResolutionException;
use App\Service\Transport\Shipment\Order\Chat\ShipmentOrderChatManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Uid\Ulid;



#[AutoconfigureTag(ChatContextResolverInterface::SERVICE_TAG_NAME)]
class ConversationChatContextResolver implements ChatContextResolverInterface
{

    public function __construct(
        private QueryBusInterface $queryBus,
        private Security $security,
        private UserRepository $userRepository,
        private ConversationRepository $conversationRepository,
        private AbstractChatChannelRepository $channelRepository,
    ) {
    }
    public function resolveCurrentChatParticipantForChannelId(Ulid $channelId): Conversation
    {
        $channel = $this->channelRepository->find($channelId);
        if (!($channel instanceof DMChatChannel)) {
            throw new ChatChannelParticipanResolutionException("Could not resolve conversation for a non-DMChatChannel");
        }

        $user = $this->getUser();
        $conversation = $this->conversationRepository->findOneBy([
            'channel' => $channel,
            'sender' => $user,
        ]);

        if($conversation != null){
            return $conversation;
        }

        throw new ChatChannelParticipanResolutionException("Could not resolve the participant for this channel");
    }


    public function resolveCurrentChatParticipantForChannel(ChatChannel $channel): Conversation
    {
        return $this->resolveCurrentChatParticipantForChannelId($channel->getId());
    }



    private function getUser(): User
    {
        $user = $this->security->getUser();
        if (!($user instanceof User))
            throw new AccessDeniedHttpException("Resource is restricted");

        return $user;
    }
}

<?php
namespace App\Service\Chat;
use App\Entity\Chat\ChatChannel;
use App\Entity\Chat\ChatParticipant;
use Symfony\Component\Uid\Ulid;



interface ChatContextResolverInterface{

    public const SERVICE_TAG_NAME = 'chat.context.resolver';

    public function resolveCurrentChatParticipantForChannelId(Ulid $channelId): ChatParticipant;
    public function resolveCurrentChatParticipantForChannel(ChatChannel $channel): ChatParticipant;
}
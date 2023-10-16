<?php
namespace App\Service\Chat;

use App\Entity\Account\User;
use App\Entity\Chat\ChatChannel;
use App\Entity\Chat\AbstractChatParticipant;
use App\Entity\Chat\DM\DMChatChannel;
use Symfony\Component\Uid\Ulid;



interface ChatContextResolverInterface{

    public const SERVICE_TAG_NAME = 'chat.context.resolver';

    public function resolveCurrentChatParticipantForChannelId(Ulid $channelId): AbstractChatParticipant;
    public function resolveCurrentChatParticipantForChannel(ChatChannel $channel): AbstractChatParticipant;

}
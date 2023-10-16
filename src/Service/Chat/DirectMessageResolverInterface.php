<?php
namespace App\Service\Chat;

use App\Entity\Account\User;
use App\Entity\Chat\DM\Conversation;
use App\Entity\Chat\DM\DMChatChannel;

interface DirectMessageResolverInterface{

    public function resolveDMConvsersation(
        User $userA,
        User $userB,
        bool $save = false
    ): DMChatChannel;

    
    public function resolveSendingConversation(User $userA, User $userB, bool $save = false): Conversation;
    
}
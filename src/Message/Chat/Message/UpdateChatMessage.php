<?php

namespace App\Message\Chat\Message;

use App\CQRS\Command\EntityUpdateCommandInterface;
use App\Entity\Chat\ChatMessage;
use Symfony\Component\Uid\Ulid;
final class UpdateChatMessage extends ChatMessageMutationCommand implements EntityUpdateCommandInterface
{
    

    public function __construct(ChatMessage|Ulid|null $message = null){

        if($message !== null){

            if($message instanceof ChatChatMessage)
                $this->setUid($message->getId());
            else
                $this->setUid($message);
        }

    }
}

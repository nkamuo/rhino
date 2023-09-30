<?php

namespace App\Message\Chat\Message;

use App\CQRS\Command\EntityMutationCommandInterface;
use App\CQRS\Command\EntityMutationCommandTrait;
use App\Entity\Chat\ChatMessage;
use Symfony\Component\Uid\Ulid;

final class DeleteChatMessage implements EntityMutationCommandInterface
{
    
    use EntityMutationCommandTrait;

    public function __construct(ChatMessage|Ulid|null $message = null){

        if($message !== null){

            if($message instanceof ChatMessage)
                $this->setUid($message->getId());
            else
                $this->setUid($message);
        }

    }
}

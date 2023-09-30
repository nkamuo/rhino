<?php

namespace App\Message\Chat\Channel;

use App\CQRS\Command\EntityMutationCommandInterface;
use App\CQRS\Command\EntityMutationCommandTrait;
use App\Entity\Chat\ChatChannel;
use Symfony\Component\Uid\Ulid;

final class DeleteChannel implements EntityMutationCommandInterface
{
    
    use EntityMutationCommandTrait;

    public function __construct(ChatChannel|Ulid|null $channel = null){

        if($channel !== null){

            if($channel instanceof ChatChannel)
                $this->setUid($channel->getId());
            else
                $this->setUid($channel);
        }

    }
}

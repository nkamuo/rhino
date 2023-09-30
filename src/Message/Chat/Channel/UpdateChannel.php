<?php

namespace App\Message\Chat\Channel;

use App\CQRS\Command\EntityUpdateCommandInterface;
use App\Entity\Chat\ChatChannel;
use Symfony\Component\Uid\Ulid;
final class UpdateChannel extends ChannelMutationCommand implements EntityUpdateCommandInterface
{
    

    public function __construct(ChatChannel|Ulid|null $channel = null){

        if($channel !== null){

            if($channel instanceof ChatChannel)
                $this->setUid($channel->getId());
            else
                $this->setUid($channel);
        }

    }
}

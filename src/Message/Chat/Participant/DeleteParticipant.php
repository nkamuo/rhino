<?php

namespace App\Message\Chat\Participant;

use App\CQRS\Command\EntityMutationCommandInterface;
use App\CQRS\Command\EntityMutationCommandTrait;
use App\Entity\Chat\ChatParticipant;
use Symfony\Component\Uid\Ulid;

final class DeleteParticipant implements EntityMutationCommandInterface
{
    
    use EntityMutationCommandTrait;

    public function __construct(ChatParticipant|Ulid|null $participant = null){

        if($participant !== null){

            if($participant instanceof ChatParticipant)
                $this->setUid($participant->getId());
            else
                $this->setUid($participant);
        }

    }
}

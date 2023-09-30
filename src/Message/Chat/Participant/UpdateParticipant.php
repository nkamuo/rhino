<?php

namespace App\Message\Chat\Participant;

use App\CQRS\Command\EntityUpdateCommandInterface;
use App\Entity\Chat\ChatParticipant;
use Symfony\Component\Uid\Ulid;
final class UpdateParticipant extends ParticipantMutationCommand implements EntityUpdateCommandInterface
{
    

    public function __construct(ChatParticipant|Ulid|null $participant = null){

        if($participant !== null){

            if($participant instanceof ChatParticipant)
                $this->setUid($participant->getId());
            else
                $this->setUid($participant);
        }

    }
}

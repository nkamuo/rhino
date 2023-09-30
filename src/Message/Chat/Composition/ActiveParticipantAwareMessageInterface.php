<?php
namespace App\Message\Chat\Composition;
use App\CQRS\MessageInterface;
use Symfony\Component\Uid\Ulid;

interface ActiveParticipantAwareMessageInterface extends MessageInterface{

    public function getActiveParticipantId(): ?Ulid;
    
}
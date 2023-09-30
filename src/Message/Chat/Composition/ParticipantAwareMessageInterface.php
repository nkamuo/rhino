<?php
namespace App\Message\Chat\Composition;
use App\CQRS\MessageInterface;
use Symfony\Component\Uid\Ulid;

interface ParticipantAwareMessageInterface extends MessageInterface{

    public function getParticipantId(): ?Ulid;
    
}
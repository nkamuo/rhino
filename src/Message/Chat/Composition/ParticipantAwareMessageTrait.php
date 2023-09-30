<?php
namespace App\Message\Chat\Composition;
use Symfony\Component\Uid\Ulid;



trait ParticipantAwareMessageTrait{

    private ?Ulid $participantId = null;

    public function getParticipantId(): ?Ulid{
        return $this->participantId;
    }

    public function setParticipantId(?Ulid  $participantId): static{
        $this->participantId = $participantId;
        return $this;
    }

}
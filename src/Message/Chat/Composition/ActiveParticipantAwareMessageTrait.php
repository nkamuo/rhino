<?php
namespace App\Message\Chat\Composition;
use Symfony\Component\Uid\Ulid;



trait ActiveParticipantAwareMessageTrait{

    private ?Ulid $activeParticipantId = null;

    public function getActiveParticipantId(): ?Ulid{
        return $this->activeParticipantId;
    }

    public function setActiveParticipantId(?Ulid  $activeParticipantId): static{
        $this->activeParticipantId = $activeParticipantId;
        return $this;
    }

}
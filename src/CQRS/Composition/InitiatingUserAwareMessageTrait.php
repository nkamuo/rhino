<?php
namespace App\CQRS\Composition;
use Symfony\Component\Uid\Ulid;


trait InitiatingUserAwareMessageTrait{

    private ?Ulid $initiatingUserId = null;




    public function setIinitiatingUserId(?Ulid $initiatingUserId): static{
        $this->initiatingUserId = $initiatingUserId;
        return $this;
    }

    public function getInitiatingUserId(): ?Ulid{
        // if (!isset($this->initiatingUserId))
        //     throw new \LogicException("initiatingUserId is not yet set");

        return $this->initiatingUserId;
    }
}
<?php
namespace App\Message\Chat\Composition;
use Symfony\Component\Uid\Ulid;



trait ActiveUserAwareMessageTrait{

    private ?Ulid $activeUserId = null;

    public function getActiveUserId(): ?Ulid{
        return $this->activeUserId;
    }

    public function setActiveUserId(?Ulid  $activeUserId): static{
        $this->activeUserId = $activeUserId;
        return $this;
    }

}
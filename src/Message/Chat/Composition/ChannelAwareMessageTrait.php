<?php
namespace App\Message\Chat\Composition;
use Symfony\Component\Uid\Ulid;



trait ChannelAwareMessageTrait{

    private ?Ulid $channelId = null;

    public function getChannelId(): ?Ulid{
        return $this->channelId;
    }

    public function setChannelId(?Ulid  $channelId): static{
        $this->channelId = $channelId;
        return $this;
    }

}
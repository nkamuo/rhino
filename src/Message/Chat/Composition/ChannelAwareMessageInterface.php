<?php
namespace App\Message\Chat\Composition;
use App\CQRS\MessageInterface;
use Symfony\Component\Uid\Ulid;

interface ChannelAwareMessageInterface extends MessageInterface{

    public function getChannelId(): ?Ulid;
    
}
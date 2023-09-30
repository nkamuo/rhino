<?php
namespace App\Message\Chat\Composition;
use App\CQRS\MessageInterface;
use Symfony\Component\Uid\Ulid;

interface ActiveUserAwareMessageInterface extends MessageInterface{

    public function getActiveUserId(): ?Ulid;
    
}
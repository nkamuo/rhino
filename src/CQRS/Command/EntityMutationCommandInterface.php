<?php
namespace App\CQRS\Command;
use App\CQRS\MessageInterface;
use Symfony\Component\Uid\Ulid;

interface EntityMutationCommandInterface extends MessageInterface{

    public function getUid(): ?Ulid;

    public function setUid(?Ulid $uid): static;
}
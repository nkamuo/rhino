<?php

namespace App\Entity\Chat;

use App\Repository\Chat\ChatBusinessParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity(repositoryClass: ChatBusinessParticipantRepository::class)]
class ChatBusinessParticipant extends ChatParticipant
{

    #[ORM\Column(type: 'ulid', nullable: true)]
    private ?Ulid $businessId = null;

    public function getBusinessId(): ?Ulid
    {
        return $this->businessId;
    }

    public function setBusinessId(?Ulid $businessId): self
    {
        $this->businessId = $businessId;

        return $this;
    }
}

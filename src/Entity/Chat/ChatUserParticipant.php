<?php

namespace App\Entity\Chat;

use App\Entity\User\User;
use App\Repository\Chat\ChatUserParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;


#[GQL\Type()]
#[ORM\Entity(repositoryClass: ChatUserParticipantRepository::class)]
class ChatUserParticipant extends ChatParticipant
{

    // #[GQL\Field()]
    // #[ORM\ManyToOne]
    // #[ORM\JoinColumn(nullable: true)]
    // private ?User $user = null;

    #[GQL\Field(type: 'Ulid')]
    #[ORM\Column(type: 'ulid', nullable: true)]
    private ?Ulid $userId = null;
    // public function getUser(): ?User
    // {
    //     return $this->user;
    // }

    // public function setUser(?User $user): self
    // {
    //     $this->user = $user;

    //     return $this;
    // }

    public function getUserId(): ?Ulid
    {
        return $this->userId;
    }

    public function setUserId(?Ulid $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}

<?php

namespace App\Entity\Chat;

use App\Repository\Chat\ChatParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type(
    interfaces: [
        'AbstractChatParticipant'
    ]
)]
#[ORM\Entity(repositoryClass: ChatParticipantRepository::class)]
class ChatParticipant extends AbstractChatParticipant
{
   


    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'chatParticipants')]
    private ?ChatChannelRole $role = null;

    #[GQL\Field()]
    #[ORM\Column(length: 128, nullable: true)]
    private ?string $name = null;


    public function getRole(): ?ChatChannelRole
    {
        return $this->role;
    }

    public function setRole(?ChatChannelRole $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

}

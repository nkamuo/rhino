<?php

namespace App\Entity\Chat\DM;

use App\Entity\Account\User;
use App\Entity\Chat\AbstractChatParticipant;
use App\Repository\Chat\DM\ConversationRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type(
    name: 'ChatDMConversation',
    interfaces: ['AbstractChatParticipant'],
)]
#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ORM\UniqueConstraint(fields: [
    'sender', 'receiver'
])]
class Conversation extends AbstractChatParticipant
{

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'conversations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sender = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $receiver = null;


    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        if ($sender && $sender == $this->receiver) {
            throw new \InvalidArgumentException("The sender and receiver in a conversation cannot reference the same user");
        }
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): static
    {
        if ($receiver && $receiver == $this->sender) {
            throw new \InvalidArgumentException("The sender and receiver in a conversation cannot reference the same user");
        }
        $this->receiver = $receiver;

        return $this;
    }
}

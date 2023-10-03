<?php

namespace App\Entity\Chat\DM;

use App\Entity\Chat\AbstractMessage;
use App\Repository\Chat\DM\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type(name:'ChatDirectMessage')]
#[ORM\Table('chat_direct_messages')]
#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message extends AbstractMessage
{

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conversation $conversation = null;

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }
}

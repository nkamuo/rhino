<?php

namespace App\Entity\Chat;

use App\Repository\Chat\ChatMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ChatMessageRepository::class)]
class ChatMessage extends AbstractMessage
{

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChatChannel $channel = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?ChatSubject $subject = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChatParticipant $participant = null;


    public function getChannel(): ?ChatChannel
    {
        return $this->channel;
    }

    public function setChannel(?ChatChannel $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getSubject(): ?ChatSubject
    {
        return $this->subject;
    }

    public function setSubject(?ChatSubject $subject): self
    {
        $this->subject = $subject;

        return $this;
    }


    public function getParticipant(): ?ChatParticipant
    {
        return $this->participant;
    }

    public function setParticipant(?ChatParticipant $participant): self
    {
        $this->participant = $participant;

        return $this;
    }
}

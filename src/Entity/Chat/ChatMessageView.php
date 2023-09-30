<?php

namespace App\Entity\Chat;

use App\Repository\Chat\ChatMessageViewRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ChatMessageViewRepository::class)]
class ChatMessageView
{
    #[GQL\Field(type: 'Ulid')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: UlidType::NAME)]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'chatMessageViews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChatMessage $message = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    private ?ChatParticipant $participant = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $viewedAt = null;

    public function __construct(?Ulid $id = null){
        $this->id = $id;
        $this->viewedAt = new \DateTimeImmutable();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getMessage(): ?ChatMessage
    {
        return $this->message;
    }

    public function setMessage(?ChatMessage $message): self
    {
        $this->message = $message;

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

    public function getViewedAt(): ?\DateTimeImmutable
    {
        return $this->viewedAt;
    }

    public function setViewedAt(\DateTimeImmutable $viewedAt): self
    {
        $this->viewedAt = $viewedAt;

        return $this;
    }
}

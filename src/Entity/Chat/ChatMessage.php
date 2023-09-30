<?php

namespace App\Entity\Chat;

use App\Repository\Chat\ChatMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ChatMessageRepository::class)]
class ChatMessage
{
    #[GQL\Field(type: 'Ulid')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: UlidType::NAME)]
    private ?Ulid $id = null;

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

    #[GQL\Field()]
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $title = null;          //VERY MUCH OPTIONAL

    #[GQL\Field()]
    #[ORM\Column(length: 4000)]
    private ?string $body = null;

    
    /**
     * @var ChatMessageAttachment[]
     */
    #[GQL\Field()]
    #[ORM\OneToMany(mappedBy: 'message', targetEntity: ChatMessageAttachment::class, orphanRemoval: true)]
    private Collection $attachments;

    /**
     * @var ChatMessageView[]
     */
    #[GQL\Field()]
    #[ORM\OneToMany(mappedBy: 'message', targetEntity: ChatMessageView::class, orphanRemoval: true)]
    private Collection $chatMessageViews;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $sentAt = null;

    
    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;



    public function __construct(?Ulid $id = null)
    {
        $this->attachments = new ArrayCollection();
        $this->chatMessageViews = new ArrayCollection();
        $this->id = $id;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

     /**
     * @return Collection<int, ChatMessageAttachment>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(ChatMessageAttachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->setMessage($this);
        }

        return $this;
    }

    public function removeAttachment(ChatMessageAttachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getMessage() === $this) {
                $attachment->setMessage(null);
            }
        }

        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(?\DateTimeImmutable $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

   

    /**
     * @return Collection<int, ChatMessageView>
     */
    public function getChatMessageViews(): Collection
    {
        return $this->chatMessageViews;
    }

    public function addChatMessageView(ChatMessageView $chatMessageView): self
    {
        if (!$this->chatMessageViews->contains($chatMessageView)) {
            $this->chatMessageViews->add($chatMessageView);
            $chatMessageView->setMessage($this);
        }

        return $this;
    }

    public function removeChatMessageView(ChatMessageView $chatMessageView): self
    {
        if ($this->chatMessageViews->removeElement($chatMessageView)) {
            // set the owning side to null (unless already changed)
            if ($chatMessageView->getMessage() === $this) {
                $chatMessageView->setMessage(null);
            }
        }

        return $this;
    }

}

<?php

namespace App\Entity\Chat;

use App\Entity\Chat\DM\DMChatChannel;
use App\Repository\Chat\AbstractChatChannelRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Resolver\TypeResolver;

#[GQL\TypeInterface(
    resolveType: 'value.resolveType(typeResolver,value)'
)]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\Entity(repositoryClass: AbstractChatChannelRepository::class)]
class AbstractChatChannel
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $code = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subject = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $participantCount = 0;

    /**
     * @var Collection<int,AbstractChatParticipant>|AbstractChatParticipant[]
     */
    #[GQL\Field(type: '[AbstractChatParticipant!]!')]
    #[ORM\OneToMany(mappedBy: 'channel', targetEntity: AbstractChatParticipant::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $participants;

    /**
     * @var Collection<int,ChatSubject>
     */
    #[GQL\Field(type: '[ChatSubject!]!')]
    #[ORM\OneToMany(mappedBy: 'channel', targetEntity: ChatSubject::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $subjects;

    /**
     * @var Collection<int,ChatMessage>
     */
    #[ORM\OneToMany(mappedBy: 'channel', targetEntity: ChatMessage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $messages;

    /**
     * @var Collection<int,ChatChannelRole>
     */
    #[GQL\Field(type: '[ChatChannelRole!]!')]
    #[ORM\OneToMany(mappedBy: 'channel', targetEntity: ChatChannelRole::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $chatChannelRoles;

    #[GQL\Field()]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $title = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subtitle = null;


    public function __construct(?Ulid $id = null)
    {
        $this->id = $id;
        $this->participants = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->subjects = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->chatChannelRoles = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

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
     * @return Collection<int, AbstractChatParticipant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(AbstractChatParticipant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setChannel($this);
        }

        $this->updateParticipantCount();
        return $this;
    }

    public function removeParticipant(AbstractChatParticipant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getChannel() === $this) {
                $participant->setChannel(null);
            }
        }

        $this->updateParticipantCount();
        return $this;
    }

    /**
     * @return Collection<int, ChatSubject>
     */
    public function getSubjects(): Collection
    {
        return $this->subjects;
    }

    public function addSubject(ChatSubject $subject): self
    {
        if (!$this->subjects->contains($subject)) {
            $this->subjects->add($subject);
            $subject->setChannel($this);
        }

        return $this;
    }

    public function removeSubject(ChatSubject $subject): self
    {
        if ($this->subjects->removeElement($subject)) {
            // set the owning side to null (unless already changed)
            if ($subject->getChannel() === $this) {
                $subject->setChannel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ChatMessage>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(ChatMessage $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setChannel($this);
        }

        return $this;
    }

    public function removeMessage(ChatMessage $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getChannel() === $this) {
                $message->setChannel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ChatChannelRole>
     */
    public function getChatChannelRoles(): Collection
    {
        return $this->chatChannelRoles;
    }

    public function addChatChannelRole(ChatChannelRole $chatChannelRole): self
    {
        if (!$this->chatChannelRoles->contains($chatChannelRole)) {
            $this->chatChannelRoles->add($chatChannelRole);
            $chatChannelRole->setChannel($this);
        }

        return $this;
    }

    public function removeChatChannelRole(ChatChannelRole $chatChannelRole): self
    {
        if ($this->chatChannelRoles->removeElement($chatChannelRole)) {
            // set the owning side to null (unless already changed)
            if ($chatChannelRole->getChannel() === $this) {
                $chatChannelRole->setChannel(null);
            }
        }

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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }


    public function updateParticipantCount(): self
    {
        $this->participantCount = $this->participants->count();
        return $this;
    }


    public function resolveType(TypeResolver $typeResolver, self $value)
    {

        if ($value instanceof ChatChannel) {
            return $typeResolver->resolve('ChatChannel');
        }
        if ($value instanceof DMChatChannel) {
            return $typeResolver->resolve('DMChatChannel');
        }
        throw new \LogicException(sprintf("Cannot resolve instance of %s as an implementaion of %s", get_class($value), self::class));
    }
}

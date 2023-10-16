<?php

namespace App\Entity\Chat;

use App\Repository\Chat\ChatChannelRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;


#[GQL\Type()]
#[ORM\Entity(repositoryClass: ChatChannelRoleRepository::class)]
class ChatChannelRole
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'chatChannelRoles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChatChannel $channel = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64)]
    private ?string $code = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * 
     */
    #[GQL\Field(type: '[ChatParticipant!]!')]
    #[ORM\OneToMany(mappedBy: 'role', targetEntity: ChatParticipant::class, cascade: ['persist', 'remove'],)]
    private Collection $chatParticipants;

    #[ORM\Column]
    private ?int $maxNumOfParticipant = null;

    public function __construct()
    {
        $this->chatParticipants = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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
     * @return Collection<int, ChatParticipant>
     */
    public function getChatParticipants(): Collection
    {
        return $this->chatParticipants;
    }

    public function addChatParticipant(ChatParticipant $chatParticipant): self
    {
        if (!$this->chatParticipants->contains($chatParticipant)) {
            $this->chatParticipants->add($chatParticipant);
            $chatParticipant->setRole($this);
        }

        return $this;
    }

    public function removeChatParticipant(ChatParticipant $chatParticipant): self
    {
        if ($this->chatParticipants->removeElement($chatParticipant)) {
            // set the owning side to null (unless already changed)
            if ($chatParticipant->getRole() === $this) {
                $chatParticipant->setRole(null);
            }
        }

        return $this;
    }

    public function getMaxNumOfParticipant(): ?int
    {
        return $this->maxNumOfParticipant;
    }

    public function setMaxNumOfParticipant(int $maxNumOfParticipant): self
    {
        $this->maxNumOfParticipant = $maxNumOfParticipant;

        return $this;
    }
}

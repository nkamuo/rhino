<?php

namespace App\Entity\Chat;

use App\Entity\Chat\DM\Conversation;
use App\Repository\Chat\AbstractChatParticipantRepository;
use Overblog\GraphQLBundle\Resolver\TypeResolver;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\TypeInterface(
    resolveType: 'value.resolveType(typeResolver,value)'
)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\Entity(repositoryClass: AbstractChatParticipantRepository::class)]
abstract class AbstractChatParticipant
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
    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AbstractChatChannel $channel = null;

    
    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    
    public function __construct(?Ulid $id = null){
        $this->id = $id;
        $this->createdAt = new \DateTimeImmutable();
    }


    
    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }


    
    public function getChannel(): ?AbstractChatChannel
    {
        return $this->channel;
    }

    public function setChannel(?AbstractChatChannel $channel): self
    {
        $this->channel = $channel;

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

    

    public function resolveType(TypeResolver $typeResolver, self $value)
    {

        if ($value instanceof ChatParticipant) {
            return $typeResolver->resolve('ChatParticipant');
        }
        if ($value instanceof Conversation) {
            return $typeResolver->resolve('ChatDMConversation');
        }
        throw new \LogicException(sprintf("Cannot resolve instance of %s as an implementaion of %s", get_class($value), self::class));
    }
}

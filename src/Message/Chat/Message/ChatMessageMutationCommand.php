<?php

namespace App\Message\Chat\Message;

use App\CQRS\Command\EntityMutationCommandInterface;
use App\CQRS\Command\EntityMutationCommandTrait;
use App\Entity\Addressing\Address;
use App\Message\Chat\Composition\ChannelAwareMessageInterface;
use App\Message\Chat\Composition\ChannelAwareMessageTrait;
use App\Message\Chat\Composition\ParticipantAwareMessageInterface;
use App\Message\Chat\Composition\ParticipantAwareMessageTrait;
use App\Message\Chat\Composition\SubjectAwareMessageInterface;
use App\Message\Chat\Composition\SubjectAwareMessageTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class ChatMessageMutationCommand implements EntityMutationCommandInterface, ChannelAwareMessageInterface, SubjectAwareMessageInterface, ParticipantAwareMessageInterface
{
    use EntityMutationCommandTrait;
    use ChannelAwareMessageTrait;
    use SubjectAwareMessageTrait;
    use ParticipantAwareMessageTrait;

    private ?string $title = null;          //VERY MUCH OPTIONAL

    private ?string $body = null;

    /**
     * @var Collection<int,ChatMessageAttachmentMutationCommand>
     */
    private Collection $attachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
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


    public function getAttachments(): Collection
    {
        return $this->attachments;
    }


    public function addAttachment(ChatMessageAttachmentMutationCommand | string $file)
    {
        if (is_string($file)) {
            $file = new ChatMessageAttachmentMutationCommand($file);
        }
        $this->attachments->add($file);
    }
}

<?php

namespace App\Message\Chat\Participant;

use App\CQRS\Command\EntityMutationCommandInterface;
use App\CQRS\Command\EntityMutationCommandTrait;
use App\Entity\Addressing\Address;
use App\Message\Chat\Composition\ChannelAwareMessageInterface;
use App\Message\Chat\Composition\ChannelAwareMessageTrait;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class ParticipantMutationCommand implements EntityMutationCommandInterface, ChannelAwareMessageInterface
{
    use EntityMutationCommandTrait;
    use ChannelAwareMessageTrait;

    private ?Ulid $userId = null;

    #[NotBlank]
    #[Length(min: 4, max: 64)]
    private ?string $code = null;

    
    #[NotBlank]
    #[Length(min: 4, max: 64)]
    private ?string $name = null;

    private ?string $emailAddress = null;

    private ?string $phoneNumber = null;

    private ?string $note = null;

    private ?string $description = null;

    private ?Address $address = null;


    public function getUserId(): ?Ulid{
        return $this->userId;
    }

    public function setUserId(?Ulid $userId): static{
        $this->userId = $userId;
        return $this;
    }

    public function getCode(): ?string{
        return $this->code;
    }

    public function setCode(?string $code): static{
        $this->code = $code;
        return $this;
    }

    
    public function getName(): ?string{
        return $this->name;
    }

    public function setName(?string $name): static{
        $this->name = $name;
        return $this;
    }


    
    public function getEmailAddress(): ?string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(?string $emailAddress): self
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }


    
    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    
}

<?php

namespace App\Entity\Shipment;

use App\Repository\Shipment\ShipmentOrderActivityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ShipmentOrderActivityRepository::class)]
class ShipmentOrderActivity
{
    #[GQL\Field(type:'Ulid')]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32)]
    private ?string $reference = null;

    #[GQL\Field()]
    #[ORM\Column(length: 16)]
    private ?string $type = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64)]
    private ?string $label = null;

    #[GQL\Field()]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[GQL\Field(type:'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $occuredAt = null;

    #[GQL\Field(type:'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShipmentOrder $shipmentOrder = null;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOccuredAt(): ?\DateTimeImmutable
    {
        return $this->occuredAt;
    }

    public function setOccuredAt(\DateTimeImmutable $occuredAt): static
    {
        $this->occuredAt = $occuredAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getShipmentOrder(): ?ShipmentOrder
    {
        return $this->shipmentOrder;
    }

    public function setShipmentOrder(?ShipmentOrder $shipmentOrder): static
    {
        $this->shipmentOrder = $shipmentOrder;

        return $this;
    }
}

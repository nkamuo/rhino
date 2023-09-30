<?php

namespace App\Entity\Shipment\Document;

use App\Entity\Shipment\ShipmentOrder;
use App\Repository\Shipment\Document\ShipmentDocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ShipmentDocumentRepository::class)]
class ShipmentDocument
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field(type: '[ShipmentDocumentAttachment!]!')]
    #[ORM\OneToMany(mappedBy: 'document', targetEntity: ShipmentDocumentAttachment::class, orphanRemoval: true, cascade:['persist', 'remove'])]
    private Collection $attachments;

    #[GQL\Field()]
    #[ORM\OneToOne(mappedBy: 'pickupConfirmation', cascade: ['persist', 'remove'])]
    private ?ShipmentOrder $shipmentOrder = null;

    #[GQL\Field(type: 'Json')]
    #[ORM\Column]
    private array $meta = [];

    #[GQL\Field()]
    #[ORM\Column(length: 32, enumType: ShipmentDocumentType::class)]
    private ?ShipmentDocumentType $type = null;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ShipmentDocumentAttachment>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(ShipmentDocumentAttachment $attachment): static
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->setDocument($this);
        }

        return $this;
    }

    public function removeAttachment(ShipmentDocumentAttachment $attachment): static
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getDocument() === $this) {
                $attachment->setDocument(null);
            }
        }

        return $this;
    }

    public function getShipmentOrder(): ?ShipmentOrder
    {
        return $this->shipmentOrder;
    }

    public function setShipmentOrder(?ShipmentOrder $shipmentOrder): static
    {
        // unset the owning side of the relation if necessary
        if ($shipmentOrder === null && $this->shipmentOrder !== null) {
            $this->shipmentOrder->setPickupConfirmation(null);
        }

        // set the owning side of the relation if necessary
        if ($shipmentOrder !== null && $shipmentOrder->getPickupConfirmation() !== $this) {
            $shipmentOrder->setPickupConfirmation($this);
        }

        $this->shipmentOrder = $shipmentOrder;

        return $this;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setMeta(array $meta): static
    {
        $this->meta = $meta;

        return $this;
    }

    public function getType(): ?ShipmentDocumentType
    {
        return $this->type;
    }

    public function setType(ShipmentDocumentType $type): static
    {
        $this->type = $type;

        return $this;
    }
}

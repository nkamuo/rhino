<?php

namespace App\Entity\Shipment\Document;

use App\Repository\Shipment\Document\ShipmentDocumentAttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ShipmentDocumentAttachmentRepository::class)]
class ShipmentDocumentAttachment
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32, enumType: ShipmentDocumentAttachmentType::class)]
    private ?ShipmentDocumentAttachmentType $type = null;

    #[GQL\Field()]
    #[ORM\Column(length: 225)]
    private ?string $src = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $caption = null;

    #[GQL\Field(type: 'Json')]
    #[ORM\Column]
    private array $meta = [];

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'attachments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ShipmentDocument $document = null;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getType(): ?ShipmentDocumentAttachmentType
    {
        return $this->type;
    }

    public function setType(ShipmentDocumentAttachmentType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(string $src): static
    {
        $this->src = $src;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): static
    {
        $this->caption = $caption;

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

    public function getDocument(): ?ShipmentDocument
    {
        return $this->document;
    }

    public function setDocument(?ShipmentDocument $document): static
    {
        $this->document = $document;

        return $this;
    }
}

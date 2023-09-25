<?php

namespace App\Entity\Shipment;

use App\Entity\Account\Driver;
use App\Entity\Vehicle\Vehicle;
use App\Repository\Shipment\ShipmentDriverBidRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ShipmentDriverBidRepository::class)]
class ShipmentDriverBid
{

    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Shipment $shipment = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Driver $driver = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $title = null;

    #[GQL\Field()]
    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $description = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64, enumType: ShipmentBidStatus::class)]
    private ?ShipmentBidStatus $status = ShipmentBidStatus::PENDING;

    #[GQL\Field(type: "DateTime")]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[GQL\Field(type: "DateTime")]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    private ?Vehicle $vehicle = null;

    #[GQL\Field()]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ShipmentDriverBidPrice $price = null;

    #[GQL\Field(type: "DateTime")]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $pickupAt = null;

    #[GQL\Field(type: "DateTime")]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deliveryAt = null;

    public function __construct(){
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }

    public function setShipment(?Shipment $shipment): static
    {
        $this->shipment = $shipment;

        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getStatus(): ?ShipmentBidStatus
    {
        return $this->status;
    }

    public function setStatus(ShipmentBidStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

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

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getPrice(): ?ShipmentDriverBidPrice
    {
        return $this->price;
    }

    public function setPrice(?ShipmentDriverBidPrice $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPickupAt(): ?\DateTimeImmutable
    {
        return $this->pickupAt;
    }

    public function setPickupAt(?\DateTimeImmutable $pickupAt): static
    {
        $this->pickupAt = $pickupAt;

        return $this;
    }

    public function getDeliveryAt(): ?\DateTimeImmutable
    {
        return $this->deliveryAt;
    }

    public function setDeliveryAt(?\DateTimeImmutable $deliveryAt): static
    {
        $this->deliveryAt = $deliveryAt;

        return $this;
    }
}

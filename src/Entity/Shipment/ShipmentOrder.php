<?php

namespace App\Entity\Shipment;

use App\Entity\Account\Driver;
use App\Entity\Account\User;
use App\Entity\Shipment\Document\ShipmentDocument;
use App\Entity\Vehicle\Vehicle;
use App\Repository\Shipment\ShipmentOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ShipmentOrderRepository::class)]
class ShipmentOrder
{
    #[GQL\Field(type: 'Ulid')]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Driver $driver = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $shipper = null;

    #[GQL\Field()]
    #[ORM\OneToOne(inversedBy: 'shipmentOrder', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Shipment $shipment = null;

    #[GQL\Field()]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ShipmentDriverBid $bid = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32, enumType: ShipmentOrderStatus::class)]
    private ShipmentOrderStatus $status = ShipmentOrderStatus::PENDING;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $pickupAt = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deliveryAt = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    private ?Vehicle $vehicle = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?ShipmentExecution $execution = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expectedPickupAt = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expectedDeliveryAt = null;


    #[GQL\Field(type: '[ShipmentOrderCharge!]!')]
    #[ORM\OneToMany(mappedBy: 'shipmentOrder', targetEntity: ShipmentOrderCharge::class, orphanRemoval: true)]
    private Collection $charges;

    #[GQL\Field(type: '[ShipmentOrderActivity!]!')]
    #[ORM\OneToMany(mappedBy: 'shipmentOrder', targetEntity: ShipmentOrderActivity::class, orphanRemoval: true)]
    private Collection $activities;

    #[GQL\Field()]
    #[ORM\Column]
    private ?int $activityCount = 0;

    #[GQL\Field()]
    #[ORM\Column(length: 32)]
    private ?string $code = null;

    #[GQL\Field()]
    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    #[GQL\Field()]
    #[ORM\Column]
    private ?int $subtotal = 0;

    #[GQL\Field()]
    #[ORM\Column]
    private ?int $chargeTotal = 0;

    #[GQL\Field()]
    #[ORM\Column]
    private ?int $total = 0;

    #[GQL\Field()]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ShipmentDocument $pickupConfirmation = null;

    #[GQL\Field()]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ShipmentDocument $proofOfDelivery = null;

    #[GQL\Field(type:'[ShipmentDocument!]!')]
    #[ORM\ManyToMany(targetEntity: ShipmentDocument::class, cascade:['persist', 'remove'])]
    private Collection $documents;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->activities = new ArrayCollection();
        $this->charges = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }
    public function getId(): ?Ulid
    {
        return $this->id;
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

    public function getShipper(): ?User
    {
        return $this->shipper;
    }

    public function setShipper(?User $shipper): static
    {
        $this->shipper = $shipper;

        return $this;
    }

    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }

    public function setShipment(Shipment $shipment): static
    {
        $this->shipment = $shipment;

        return $this;
    }

    public function getBid(): ?ShipmentDriverBid
    {
        return $this->bid;
    }

    public function setBid(?ShipmentDriverBid $bid): static
    {
        $this->bid = $bid;

        return $this;
    }

    public function getStatus(): ?ShipmentOrderStatus
    {
        return $this->status;
    }

    public function setStatus(ShipmentOrderStatus $status): static
    {
        $this->status = $status;

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

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

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

    public function getExecution(): ?ShipmentExecution
    {
        return $this->execution;
    }

    public function setExecution(?ShipmentExecution $execution): static
    {
        $this->execution = $execution;

        return $this;
    }

    public function getExpectedPickupAt(): ?\DateTimeImmutable
    {
        return $this->expectedPickupAt;
    }

    public function setExpectedPickupAt(?\DateTimeImmutable $expectedPickupAt): static
    {
        $this->expectedPickupAt = $expectedPickupAt;

        return $this;
    }

    public function getExpectedDeliveryAt(): ?\DateTimeImmutable
    {
        return $this->expectedDeliveryAt;
    }

    public function setExpectedDeliveryAt(?\DateTimeImmutable $expectedDeliveryAt): static
    {
        $this->expectedDeliveryAt = $expectedDeliveryAt;

        return $this;
    }

    /**
     * @return Collection<int, ShipmentOrderActivity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(ShipmentOrderActivity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setShipmentOrder($this);
        }

        $this->activityCount = $this->activities->count();
        return $this;
    }

    public function removeActivity(ShipmentOrderActivity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getShipmentOrder() === $this) {
                $activity->setShipmentOrder(null);
            }
        }
        $this->activityCount = $this->activities->count();
        return $this;
    }

    public function getActivityCount(): ?int
    {
        return $this->activityCount;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Collection<int, ShipmentOrderCharge>
     */
    public function getCharges(): Collection
    {
        return $this->charges;
    }

    public function addCharge(ShipmentOrderCharge $charge): static
    {
        if (!$this->charges->contains($charge)) {
            $this->charges->add($charge);
            $charge->setShipmentOrder($this);
        }

        return $this;
    }

    public function removeCharge(ShipmentOrderCharge $charge): static
    {
        if ($this->charges->removeElement($charge)) {
            // set the owning side to null (unless already changed)
            if ($charge->getShipmentOrder() === $this) {
                $charge->setShipmentOrder(null);
            }
        }

        return $this;
    }

    public function getSubtotal(): ?int
    {
        return $this->subtotal;
    }

    public function setSubtotal(int $subtotal): static
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getChargeTotal(): ?int
    {
        return $this->chargeTotal;
    }

    public function setChargeTotal(int $chargeTotal): static
    {
        $this->chargeTotal = $chargeTotal;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getPickupConfirmation(): ?ShipmentDocument
    {
        return $this->pickupConfirmation;
    }

    public function setPickupConfirmation(?ShipmentDocument $pickupConfirmation): static
    {
        $crnt = $this->pickupConfirmation;
        if (!($pickupConfirmation)) {
            if ($crnt)
                $this->removeDocument($crnt);
        } else {
            $this->addDocument($pickupConfirmation);
        }

        $this->pickupConfirmation = $pickupConfirmation;

        return $this;
    }

    public function getProofOfDelivery(): ?ShipmentDocument
    {
        return $this->proofOfDelivery;
    }

    public function setProofOfDelivery(?ShipmentDocument $proofOfDelivery): static
    {
        $crnt = $this->proofOfDelivery;
        if (!($proofOfDelivery)) {
            if ($crnt)
                $this->removeDocument($crnt);
        } else {
            $this->addDocument($proofOfDelivery);
        }

        $this->proofOfDelivery = $proofOfDelivery;

        return $this;
    }

    /**
     * @return Collection<int, ShipmentDocument>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(ShipmentDocument $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
        }

        return $this;
    }

    public function removeDocument(ShipmentDocument $document): static
    {
        $this->documents->removeElement($document);

        return $this;
    }
}

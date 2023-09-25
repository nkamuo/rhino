<?php

namespace App\Entity\Shipment;

use App\Entity\Account\User;
use App\Entity\Addressing\Routing\Route;
use App\Entity\Addressing\UserAddress;
use App\Repository\Shipment\ShipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ShipmentRepository::class)]
class Shipment
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64, enumType: ShipmentType::class)]
    private ?ShipmentType $type = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(cascade: ['persist',])]
    private ?UserAddress $billingAddress = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(cascade: ['persist',])]
    private ?UserAddress $originAddress = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(cascade: ['persist',])]
    private ?UserAddress $destinationAddress = null;

    #[GQL\Field(type: '[ShipmentItem!]!')]
    #[ORM\OneToMany(mappedBy: 'shipment', targetEntity: ShipmentItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $items;

    #[GQL\Field(type: '[ShipmentDriverBid!]!')]
    #[ORM\OneToMany(mappedBy: 'shipment', targetEntity: ShipmentDriverBid::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $bids;

    #[GQL\Field()]
    #[ORM\OneToOne(inversedBy: 'shipment', cascade: ['persist', 'remove'])]
    private ?ShipmentBudget $budget = null;

    #[GQL\Field(type: "DateTime")]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[GQL\Field(type: "DateTime")]
    #[ORM\Column()]
    private ?\DateTimeImmutable $createdAt = null;

    #[GQL\Field()]
    #[ORM\ManyToOne(cascade: ['persist',])]     // AVOID REMOVE CUS MORE THAN ONE SHIPMENTS CAN USE THE SAME ROUTE
    #[ORM\JoinColumn(nullable: false)]
    private ?Route $route = null;

    #[GQL\Field()]
    #[ORM\Column(
        length: 64,
        enumType: ShipmentStatus::class,
        options: [
            'default' => ShipmentStatus::PENDING,
        ]
    )]
    private ?ShipmentStatus $status = ShipmentStatus::PENDING;

    #[GQL\Field(type: "DateTime")]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $pickupAt = null;

    #[GQL\Field(type: "DateTime")]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deliveryAt = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32)]
    private ?string $code = null;

    #[ORM\Column(
        options: [
            'default' => 0,
        ]
    )]
    private ?int $totalWeight = null;


    public function __construct(?Ulid $id = null)
    {
        $this->id = $id;
        $this->items = new ArrayCollection();
        $this->bids = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }


    public function getType(): ?ShipmentType
    {
        return $this->type;
    }

    public function setType(ShipmentType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getBillingAddress(): ?UserAddress
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?UserAddress $billingAddress): static
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getOriginAddress(): ?UserAddress
    {
        return $this->originAddress;
    }

    public function setOriginAddress(?UserAddress $originAddress): static
    {
        $this->originAddress = $originAddress;

        return $this;
    }

    public function getDestinationAddress(): ?UserAddress
    {
        return $this->destinationAddress;
    }

    public function setDestinationAddress(?UserAddress $destinationAddress): static
    {
        $this->destinationAddress = $destinationAddress;

        return $this;
    }

    /**
     * @return Collection<int, ShipmentItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(ShipmentItem $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setShipment($this);
        }

        return $this;
    }

    public function removeItem(ShipmentItem $item): static
    {
        if ($this->items->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getShipment() === $this) {
                $item->setShipment(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShipmentDriverBid>
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(ShipmentDriverBid $bid): static
    {
        if (!$this->bids->contains($bid)) {
            $this->bids->add($bid);
            $bid->setShipment($this);
        }

        return $this;
    }

    public function removeBid(ShipmentDriverBid $bid): static
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getShipment() === $this) {
                $bid->setShipment(null);
            }
        }

        return $this;
    }

    public function getBudget(): ?ShipmentBudget
    {
        return $this->budget;
    }

    public function setBudget(?ShipmentBudget $budget): static
    {
        $this->budget = $budget;

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

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }

    public function setRoute(?Route $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getStatus(): ?ShipmentStatus
    {
        return $this->status;
    }

    public function setStatus(ShipmentStatus $status): static
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    #[GQL\Field(name: 'weight')]
    public function calculateWeight(): int
    {
        $weight = 0;
        foreach ($this->getItems() as $item) {
            if ($iWeight = $item->getProduct()?->getWeight()) {
                $weight += $iWeight;
            }
        }
        return $weight;
    }

    public function getTotalWeight(): ?int
    {
        return $this->totalWeight;
    }

    public function setTotalWeight(int $totalWeight): static
    {
        $this->totalWeight = $totalWeight;

        return $this;
    }
}

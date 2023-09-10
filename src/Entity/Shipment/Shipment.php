<?php

namespace App\Entity\Shipment;

use App\Entity\Account\User;
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
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    private ?UserAddress $billingAddress = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    private ?UserAddress $originAddress = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    private ?UserAddress $destinationAddress = null;

    #[GQL\Field(type:'[ShipmentItem!]!')]
    #[ORM\OneToMany(mappedBy: 'shipment', targetEntity: ShipmentItem::class, orphanRemoval: true)]
    private Collection $items;

    #[GQL\Field(type:'[ShipmentDriverBid!]!')]
    #[ORM\OneToMany(mappedBy: 'shipment', targetEntity: ShipmentDriverBid::class, orphanRemoval: true)]
    private Collection $bids;

    public function __construct(?Ulid $id = null)
    {
        $this->id = $id;
        $this->items = new ArrayCollection();
        $this->bids = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
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
}

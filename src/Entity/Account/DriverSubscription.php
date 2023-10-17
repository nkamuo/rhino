<?php

namespace App\Entity\Account;

use App\Repository\Account\DriverSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DriverSubscriptionRepository::class)]
class DriverSubscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128, nullable: true)]
    private ?string $stripeSubscriptionId = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(mappedBy: 'subscription', cascade: ['persist', 'remove'])]
    private ?Driver $driver = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStripeSubscriptionId(): ?string
    {
        return $this->stripeSubscriptionId;
    }

    public function setStripeSubscriptionId(?string $stripeSubscriptionId): static
    {
        $this->stripeSubscriptionId = $stripeSubscriptionId;

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

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): static
    {
        // unset the owning side of the relation if necessary
        if ($driver === null && $this->driver !== null) {
            $this->driver->setSubscription(null);
        }

        // set the owning side of the relation if necessary
        if ($driver !== null && $driver->getSubscription() !== $this) {
            $driver->setSubscription($this);
        }

        $this->driver = $driver;

        return $this;
    }
}

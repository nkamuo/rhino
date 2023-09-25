<?php

namespace App\Entity\Shipment;

use App\Repository\Shipment\ShipmentDriverBidPriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ShipmentDriverBidPriceRepository::class)]
class ShipmentDriverBidPrice
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    #[GQL\Field()]
    #[ORM\Column]
    private ?int $amount = null;

    public function getId(): ?Ulid
    {
        return $this->id;
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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}

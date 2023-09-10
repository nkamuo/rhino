<?php

namespace App\Entity\Catalog;

use App\Repository\Catalog\ProductPriceRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: ProductPriceRepository::class)]
class ProductPrice
{
    #[GQL\Field(type: "Ulid")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    #[GQL\Field()]
    #[ORM\Column]
    private ?int $amount = null;

    public function getId(): ?int
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

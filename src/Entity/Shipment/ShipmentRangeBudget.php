<?php

namespace App\Entity\Shipment;

use App\Repository\Shipment\ShipmentRangeBudgetRepository;
use Doctrine\ORM\Mapping as ORM;
use GraphQL\Type\Definition\Type;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Resolver\TypeResolver;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Expression("value.maxPrice > value.minPrice")]
#[GQL\Type()]
#[ORM\Entity(repositoryClass: ShipmentRangeBudgetRepository::class)]
class ShipmentRangeBudget extends ShipmentBudget
{
    #[GQL\Field()]
    #[ORM\Column(nullable: true)]
    private ?int $minPrice = null;

    #[GQL\Field()]
    #[ORM\Column(nullable: true)]
    private ?int $maxPrice = null;

    public function getMinPrice(): ?int
    {
        return $this->minPrice;
    }

    public function setMinPrice(int $minPrice): static
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(int $maxPrice): static
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    public function resolveGQLType(TypeResolver $typeResolver): Type{
        return $typeResolver->resolve('ShipmentRangeBudget');
    }
}

<?php

namespace App\Entity\Shipment;

use App\Repository\Shipment\ShipmentFixedBudgetRepository;
use GraphQL\Type\Definition\Type;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Resolver\TypeResolver;
use Symfony\Component\Validator\Constraints as Assert;

#[Assert\Expression("value.price is not null")]
#[GQL\Type()]
#[ORM\Entity(repositoryClass: ShipmentFixedBudgetRepository::class)]
class ShipmentFixedBudget extends ShipmentBudget
{

    #[Assert\GreaterThan(0)]
    #[GQL\Field()]
    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }
    
    public function resolveGQLType(TypeResolver $typeResolver): Type{
        return $typeResolver->resolve('ShipmentFixedBudget');
    }
}

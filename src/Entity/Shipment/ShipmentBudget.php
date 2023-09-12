<?php

namespace App\Entity\Shipment;

use App\Repository\Shipment\ShipmentBudgetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;
use Overblog\GraphQLBundle\Annotation as GQL;
use Overblog\GraphQLBundle\Resolver\TypeResolver;
use GraphQL\Type\Definition\Type;

#[GQL\TypeInterface(resolveType:'value.resolveGQLType(typeResolver)')]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\Entity(repositoryClass: ShipmentBudgetRepository::class)]
abstract class ShipmentBudget
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

    #[ORM\OneToOne(mappedBy: 'budget', cascade: ['persist', 'remove'])]
    private ?Shipment $shipment = null;

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

    abstract function resolveGQLType( TypeResolver $typeResolver): Type;

    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }

    public function setShipment(?Shipment $shipment): static
    {
        // unset the owning side of the relation if necessary
        if ($shipment === null && $this->shipment !== null) {
            $this->shipment->setBudget(null);
        }

        // set the owning side of the relation if necessary
        if ($shipment !== null && $shipment->getBudget() !== $this) {
            $shipment->setBudget($this);
        }

        $this->shipment = $shipment;

        return $this;
    }
}

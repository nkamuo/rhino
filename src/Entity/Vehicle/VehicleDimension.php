<?php

namespace App\Entity\Vehicle;

use App\Repository\Vehicle\VehicleDimensionRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: VehicleDimensionRepository::class)]
class VehicleDimension
{
    #[GQL\Field(type: 'Ulid')]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column]
    private ?int $length = null;

    #[GQL\Field()]
    #[ORM\Column]
    private ?int $width = null;

    #[GQL\Field()]
    #[ORM\Column]
    private ?int $height = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32, nullable: true)]
    private ?string $unit = null;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): static
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): static
    {
        $this->unit = $unit;

        return $this;
    }
}

<?php

namespace App\Entity\Addressing;

use App\Repository\Addressing\CoordinateRepository;
use Brick\Geo\Point;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Table(name:'address_coordinates')]
#[ORM\Entity(repositoryClass: CoordinateRepository::class)]
class Coordinate
{
    #[GQL\Field(type: 'Ulid')]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column]
    private ?float $latitude = null;

    #[GQL\Field()]
    #[ORM\Column]
    private ?float $longitude = null;

    #[GQL\Field()]
    #[ORM\Column(nullable: true)]
    private ?float $altitude = null;

    #[GQL\Field()]
    #[ORM\Column(nullable: true)]
    private ?float $accuracy = null;

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function setAltitude(?float $altitude): static
    {
        $this->altitude = $altitude;

        return $this;
    }

    public function getAccuracy(): ?float
    {
        return $this->accuracy;
    }

    public function setAccuracy(?float $accuracy): static
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function toPoint(): Point{
        return  Point::fromText("POINT({$this->latitude} {$this->longitude})");
    }
}

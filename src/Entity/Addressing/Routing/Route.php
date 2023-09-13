<?php

namespace App\Entity\Addressing\Routing;

use App\Repository\Addressing\Routing\RouteRepository;
use Brick\Geo\Point;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[GQL\Field(type: 'Ulid')]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

    #[GQL\Field()]
    #[ORM\Column(length: 64)]
    private ?string $code = null;

    #[GQL\Field()]
    #[ORM\Column(length: 128, nullable: true)]
    private ?string $name = null;

    #[GQL\Field(type: 'Point')]
    #[ORM\Column(type: 'Point', nullable: true)]
    private $startPoint = null;

    #[GQL\Field(type: 'Point')]
    #[ORM\Column(type: 'Point', nullable: true)]
    private $endPoint = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $startPlaceId = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $endPlaceId = null;

    #[GQL\Field(type: 'Geometry')]
    #[ORM\Column(type: 'LineString')]
    private $polyline = null;

    #[GQL\Field()]
    #[ORM\Column(nullable: true)]
    private ?int $distance = null;

    #[GQL\Field()]
    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[GQL\Field(type: 'DateTime')]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct(?Ulid $id = null){
        $this->id = $id;
        $this->createdAt = new \DateTimeImmutable();
    }
    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartPoint(): ?Point
    {
        return $this->startPoint;
    }

    public function setStartPoint(?Point $startPoint): self
    {
        $this->startPoint = $startPoint;

        return $this;
    }

    public function getEndPoint(): ?Point
    {
        return $this->endPoint;
    }

    public function setEndPoint(Point $endPoint): self
    {
        $this->endPoint = $endPoint;

        return $this;
    }

    public function getStartPlaceId(): ?string
    {
        return $this->startPlaceId;
    }

    public function setStartPlaceId(?string $startPlaceId): self
    {
        $this->startPlaceId = $startPlaceId;

        return $this;
    }

    public function getEndPlaceId(): ?string
    {
        return $this->endPlaceId;
    }

    public function setEndPlaceId(?string $endPlaceId): self
    {
        $this->endPlaceId = $endPlaceId;

        return $this;
    }


    
    public function getPolyline()
    {
        return $this->polyline;
    }

    public function setPolyline($polyline): self
    {
        $this->polyline = $polyline;

        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(?int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}

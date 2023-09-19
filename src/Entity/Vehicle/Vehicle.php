<?php

namespace App\Entity\Vehicle;

use App\Entity\Account\Driver;
use App\Repository\Vehicle\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[GQL\Field(type:'Ulid')]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?int $id = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?VehicleType $type = null;

    #[GQL\Field()]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Driver $driver = null;

    #[GQL\Field()]
    #[ORM\Column(length: 32, enumType: VehicleStatus::class)]
    private ?VehicleStatus $status = VehicleStatus::PENDING;

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

    public function getType(): ?VehicleType
    {
        return $this->type;
    }

    public function setType(?VehicleType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): static
    {
        $this->driver = $driver;

        return $this;
    }

    public function getStatus(): ?VehicleStatus
    {
        return $this->status;
    }

    public function setStatus(VehicleStatus $status): static
    {
        $this->status = $status;

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
}

<?php

namespace App\Entity\Vehicle;

use App\Entity\Account\Driver;
use App\Repository\Vehicle\VehicleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Overblog\GraphQLBundle\Annotation as GQL;
use Symfony\Bridge\Doctrine\Types\UlidType;
use Symfony\Component\Uid\Ulid;

#[GQL\Type()]
#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[GQL\Field(type: 'Ulid')]
    #[ORM\Id]
    #[ORM\Column(type: UlidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.ulid_generator')]
    private ?Ulid $id = null;

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

    #[GQL\Field()]
    #[ORM\Column(length: 32)]
    private ?string $vin = null;

    #[GQL\Field()]
    #[ORM\Column(length: 16)]
    private ?string $licensePlateNumber = null;

    #[GQL\Field()]
    #[ORM\Column(nullable: true)]
    private ?int $maxWeightCapacity = null;

    #[GQL\Field()]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?VehicleDimension $dimension = null;

    #[GQL\Field()]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;

    #[GQL\Field()]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;


    public function __construct(?Ulid $id = null)
    {
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

    public function getVin(): ?string
    {
        return $this->vin;
    }

    public function setVin(string $vin): static
    {
        $this->vin = $vin;

        return $this;
    }

    public function getLicensePlateNumber(): ?string
    {
        return $this->licensePlateNumber;
    }

    public function setLicensePlateNumber(string $licensePlateNumber): static
    {
        $this->licensePlateNumber = $licensePlateNumber;

        return $this;
    }

    public function getMaxWeightCapacity(): ?int
    {
        return $this->maxWeightCapacity;
    }

    public function setMaxWeightCapacity(?int $maxWeightCapacity): static
    {
        $this->maxWeightCapacity = $maxWeightCapacity;

        return $this;
    }

    public function getDimension(): ?VehicleDimension
    {
        return $this->dimension;
    }

    public function setDimension(?VehicleDimension $dimension): static
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
